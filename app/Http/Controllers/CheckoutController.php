<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\VcardProduct;
use App\ProductOrder;
use App\User;
use App\BusinessCard;
use Exception;

class CheckoutController extends Controller
{
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Crear sesión de checkout de Stripe
     */
    public function createSession($productId)
{
    try {
        $config = \DB::table("config")->get();
        \Stripe\Stripe::setApiKey($config[10]->config_value);
        
        $product = \App\VcardProduct::findOrFail($productId);
        $businessCard = \App\BusinessCard::where("card_id", $product->card_id)->first();
        $user = \App\User::where("user_id", $businessCard->user_id)->first();
        
        // Calcular comisión (5%)
        $amount = $product->sales_price * 100; // Stripe usa centavos
        $commission = $amount * 0.05; // 5% para ClickMyLink
        
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => strtolower($product->currency ?? "usd"),
            'payment_method_types' => ['card'],
            // 'application_fee_amount' => $commission, // ← COMENTAR TEMPORALMENTE
            'metadata' => [
                'product_id' => $productId,
                'product_name' => $product->product_name,
                'user_id' => $user->user_id
            ]
        ]);
        
        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            'amount' => $product->sales_price,
            'product_name' => $product->product_name
        ]);
    } catch (\Exception $e) {
        return response()->json(["error" => $e->getMessage()], 500);
    }
}

    /**
     * Página de éxito después del pago
     */
    public function success(Request $request)
    {
        $session_id = $request->get('session_id');
        
        try {
            // Recuperar sesión de Stripe
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            
            // Buscar la orden
            $order = ProductOrder::where('stripe_session_id', $session_id)->first();
            
            if ($order) {
                $product = VcardProduct::find($order->product_id);
                
                return view('checkout.success', compact('order', 'product', 'session'));
            }

        } catch (Exception $e) {
            Log::error('Error en página de éxito: ' . $e->getMessage());
        }

        return view('checkout.success')->with('message', 'Pago procesado exitosamente');
    }

    /**
     * Webhook para confirmar pagos
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);

            // Manejar diferentes tipos de eventos
            switch ($event['type']) {
                case 'checkout.session.completed':
                    $this->handleCheckoutCompleted($event['data']['object']);
                    break;
                    
                case 'payment_intent.succeeded':
                    $this->handlePaymentSucceeded($event['data']['object']);
                    break;
                    
                default:
                    Log::info('Evento de webhook no manejado: ' . $event['type']);
            }

            return response()->json(['status' => 'success']);

        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Error de verificación de webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (Exception $e) {
            Log::error('Error procesando webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook error'], 500);
        }
    }

    /**
     * Manejar checkout completado
     */
    private function handleCheckoutCompleted($session)
    {
        try {
            // Buscar la orden por session_id
            $order = ProductOrder::where('stripe_session_id', $session['id'])->first();
            
            if ($order) {
                $order->update([
                    'buyer_email' => $session['customer_details']['email'] ?? '',
                    'buyer_name' => $session['customer_details']['name'] ?? '',
                    'status' => 'completed'
                ]);

                Log::info('Orden completada: ' . $order->order_id);
                
                // Aquí puedes añadir lógica adicional como:
                // - Enviar email de confirmación
                // - Generar enlace de descarga para productos digitales
                // - Notificar al vendedor
            }

        } catch (Exception $e) {
            Log::error('Error manejando checkout completado: ' . $e->getMessage());
        }
    }

    /**
     * Manejar pago exitoso
     */
    private function handlePaymentSucceeded($payment_intent)
    {
        Log::info('Pago exitoso: ' . $payment_intent['id']);

        try {
            // Obtener metadata del payment intent
            $metadata = $payment_intent['metadata'] ?? [];
            
            if (empty($metadata['product_id']) && empty($metadata['service_id'])) {
                Log::info('No hay metadata de producto/servicio en payment intent: ' . $payment_intent['id']);
                return;
            }

            // Obtener configuración de comisión
            $commissionRate = \DB::table('config')
                ->where('config_key', 'platform_commission_rate')
                ->value('config_value') ?? 5.00;

            // Calcular montos
            $totalAmount = $payment_intent['amount'] / 100; // Convertir de centavos
            $platformCommission = $totalAmount * ($commissionRate / 100);
            $creatorEarning = $totalAmount - $platformCommission;

            // Determinar si es producto o servicio
            $earningType = !empty($metadata['product_id']) ? 'product_sale' : 'service_sale';
            $itemId = $metadata['product_id'] ?? $metadata['service_id'];
            $itemName = $metadata['product_name'] ?? $metadata['service_name'] ?? 'Venta';

            // Obtener usuario creador
            $creatorUserId = $this->getCreatorUserId($itemId, $earningType);
            
            if (!$creatorUserId) {
                Log::error('No se pudo obtener creator_user_id para item: ' . $itemId);
                return;
            }

            // Crear registro de earning
            \DB::table('referrals')->insert([
                'user_id' => $creatorUserId,
                'referred_user_id' => null, // No aplica para ventas directas
                'is_registered' => 1,       // Marcado como válido
                'is_subscribed' => 1,       // Marcado como pagado
                'earning_type' => $earningType,
                'referral_scheme' => json_encode([
                    'referral_amount' => $creatorEarning,
                    'original_amount' => $totalAmount,
                    'platform_commission' => $platformCommission,
                    'commission_rate' => $commissionRate . '%',
                    'item_id' => $itemId,
                    'item_name' => $itemName,
                    'payment_intent_id' => $payment_intent['id'],
                    'sale_date' => date('Y-m-d H:i:s')
                ]),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info('Earning creado exitosamente', [
                'creator_user_id' => $creatorUserId,
                'earning_type' => $earningType,
                'creator_earning' => $creatorEarning,
                'platform_commission' => $platformCommission
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear earning: ' . $e->getMessage());
        }
    }

    /**
     * Obtener user_id del creador basado en el item vendido
     */
    private function getCreatorUserId($itemId, $earningType)
    {
        try {
            if ($earningType === 'product_sale') {
                // Obtener creador desde producto
                $product = \DB::table('vcard_products')->where('id', $itemId)->first();
                if ($product) {
                    $businessCard = \DB::table('business_cards')->where('card_id', $product->card_id)->first();
                    return $businessCard ? $businessCard->user_id : null;
                }
            } elseif ($earningType === 'service_sale') {
                // Obtener creador desde servicio
                $service = \DB::table('services')->where('id', $itemId)->first();
                if ($service) {
                    $businessCard = \DB::table('business_cards')->where('card_id', $service->card_id)->first();
                    return $businessCard ? $businessCard->user_id : null;
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error al obtener creator user_id: ' . $e->getMessage());
            return null;
        }
    }

    public function createServiceSession($serviceId)
    {
       try {
        $config = \DB::table("config")->get();
        \Stripe\Stripe::setApiKey($config[10]->config_value);
        
        $service = \App\Service::findOrFail($serviceId);
        $businessCard = \App\BusinessCard::where("card_id", $service->card_id)->first();
        $user = \App\User::where("user_id", $businessCard->user_id)->first();

        // Calcular comisión (5%)
        $amount = $service->price * 100; // Stripe usa centavos
        $commission = $amount * 0.05; // 5% para ClickMyLink

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => strtolower($service->currency ?? "usd"),
            'payment_method_types' => ['card'],
            // 'application_fee_amount' => $commission, // ← COMENTAR TEMPORALMENTE
            'metadata' => [
                'service_id' => $serviceId,
                'service_name' => $service->service_name,
                'user_id' => $user->user_id
            ]
        ]);

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            'amount' => $service->price,
            'service_name' => $service->service_name
        ]);
    } catch (\Exception $e) {
        return response()->json(["error" => $e->getMessage()], 500);
        }
    }

}
