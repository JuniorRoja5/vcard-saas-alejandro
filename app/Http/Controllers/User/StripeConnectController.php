<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use Exception;

class StripeConnectController extends Controller
{
    public function __construct()
    {
        // Configurar Stripe con las claves del entorno
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Mostrar página de conexión con Stripe
     */
    public function index()
    {
        $user = Auth::user();
        
        // Verificar si el usuario ya tiene cuenta de Stripe
        if ($user->stripe_account_id && $user->stripe_onboarding_completed) {
            return redirect()->route('user.dashboard')->with('success', trans('Tu cuenta de Stripe ya está configurada.'));
        }

        return view('user.pages.stripe-connect', compact('user'));
    }

    /**
     * Crear cuenta Express de Stripe automáticamente
     */
    public function createAccount(Request $request)
    {
        try {
            $user = Auth::user();

            // Si ya tiene cuenta, redirigir al onboarding
            if ($user->stripe_account_id) {
                return $this->handleOnboarding();
            }

            // Crear nueva cuenta Express
            $account = \Stripe\Account::create([
                'type' => 'express',
                'email' => $user->email,
                'country' => $request->input('country', 'US'),
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
                'settings' => [
                    'payouts' => [
                        'schedule' => [
                            'interval' => 'weekly',
                            'weekly_anchor' => 'friday'
                        ]
                    ]
                ]
            ]);

            // Guardar account_id en la base de datos
            $user->update([
                'stripe_account_id' => $account->id
            ]);

            return $this->handleOnboarding();

        } catch (Exception $e) {
            \Log::error('Error creando cuenta Stripe: ' . $e->getMessage());
            return redirect()->back()->with('failed', trans('Error al crear cuenta de Stripe. Inténtalo de nuevo.'));
        }
    }

    /**
     * Manejar proceso de onboarding embebido
     */
    public function handleOnboarding()
    {
        try {
            $user = Auth::user();

            $account_link = \Stripe\AccountLink::create([
                'account' => $user->stripe_account_id,
                'refresh_url' => route('user.stripe.connect'),
                'return_url' => route('user.stripe.connect.return'),
                'type' => 'account_onboarding',
            ]);

            return redirect($account_link->url);

        } catch (Exception $e) {
            \Log::error('Error en onboarding Stripe: ' . $e->getMessage());
            return redirect()->back()->with('failed', trans('Error en el proceso de verificación.'));
        }
    }

    /**
     * Página de retorno después del onboarding
     */
    public function onboardingReturn()
    {
        try {
            $user = Auth::user();

            // Verificar estado de la cuenta
            $account = \Stripe\Account::retrieve($user->stripe_account_id);

            if ($account->charges_enabled && $account->payouts_enabled) {
                // Onboarding completado
                $user->update([
                    'stripe_onboarding_completed' => true
                ]);

                return redirect()->route('user.dashboard')->with('success', trans('¡Cuenta de Stripe configurada exitosamente! Ya puedes recibir pagos.'));
            } else {
                return redirect()->route('user.stripe.connect')->with('failed', trans('El proceso de verificación no se completó. Inténtalo de nuevo.'));
            }

        } catch (Exception $e) {
            \Log::error('Error verificando cuenta Stripe: ' . $e->getMessage());
            return redirect()->route('user.stripe.connect')->with('failed', trans('Error verificando la cuenta.'));
        }
    }

    /**
     * Obtener saldo disponible del usuario
     */
    public function getBalance()
    {
        try {
            $user = Auth::user();

            if (!$user->stripe_account_id) {
                return response()->json(['error' => 'No hay cuenta de Stripe configurada'], 400);
            }

            $balance = \Stripe\Balance::retrieve([], [
                'stripe_account' => $user->stripe_account_id
            ]);

            $available = 0;
            if (!empty($balance->available)) {
                foreach ($balance->available as $amount) {
                    $available += $amount->amount;
                }
            }

            return response()->json([
                'available' => $available / 100, // Convertir de centavos a euros/dólares
                'currency' => $balance->available[0]->currency ?? 'usd'
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error obteniendo saldo'], 500);
        }
    }

    /**
     * Solicitar retiro de fondos (payout)
     */
    public function requestPayout(Request $request)
    {
        try {
            $user = Auth::user();
            $amount = $request->input('amount'); // En centavos

            $payout = \Stripe\Payout::create([
                'amount' => $amount,
                'currency' => 'usd',
                'method' => 'standard',
            ], [
                'stripe_account' => $user->stripe_account_id
            ]);

            return redirect()->back()->with('success', trans('Retiro solicitado exitosamente.'));

        } catch (Exception $e) {
            \Log::error('Error en payout: ' . $e->getMessage());
            return redirect()->back()->with('failed', trans('Error procesando el retiro.'));
        }
    }
}
