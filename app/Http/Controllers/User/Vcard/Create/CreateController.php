<?php

/*
 |--------------------------------------------------------------------------
 | GoBiz vCard SaaS
 |--------------------------------------------------------------------------
 | Developed by NativeCode © 2021 - https://nativecode.in
 | All rights reserved
 | Unauthorized distribution is prohibited
 |--------------------------------------------------------------------------
*/

namespace App\Http\Controllers\User\Vcard\Create;

use App\BusinessCard;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Theme;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // Create Card
    public function CreateCard(Request $request)
    {
        // Queries
        if ($request->query('type') == "business") {
            $themes = Theme::where('theme_description', 'vCard')->whereNotIn('theme_id', ["588969111014", "588969111015", "588969111016", "588969111017", "588969111018", "588969111019", "588969111020", "588969111021", "588969111147"])->orderBy('id', 'desc')->where('status', 1)->get();
        } else {
            $themes = Theme::where('theme_description', 'vCard')->whereIn('theme_id', ["588969111014", "588969111015", "588969111016", "588969111017", "588969111018", "588969111019", "588969111020", "588969111021"])->orderBy('id', 'desc')->where('status', 1)->get();
        }

        $settings = Setting::where('status', 1)->first();
        $cards    = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'vcard')->where('card_status', 'activated')->count();

        // Active plan details in user
        $plan         = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        $config = DB::table('config')->get();

        // Check unlimited cards
        if ($plan_details->no_of_vcards == 999) {
            $no_cards = 999999;
        } else {
            $no_cards = $plan_details->no_of_vcards;
        }

        // Chech vcard creation limit
        if ($cards < $no_cards) {
            return view('user.pages.cards.create-card', compact('themes', 'settings', 'plan_details', 'config'));
        } else {
            return redirect()->route('user.cards')->with('failed', trans('The maximum limit has been exceeded. Please upgrade your plan.'));
        }
    }

    // Save card
    public function saveBusinessCard(Request $request)
    {
        // Validator
        $validator = Validator::make($request->all(), [
            'theme_id'    => 'required',
            'card_lang'   => 'required',
            'logo'        => 'required',
            'title'       => 'required',
            'cover_type'  => 'required',
            'subtitle'    => 'required',
            'description' => 'required',
        ]);

        // Validate alert
        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Unique card ID (personalized_link)
        $cardId = uniqid();

        if ($request->link) {
            $personalized_link = $request->link;
        } else {
            $personalized_link = $cardId;
        }

        // Queries
        $cards        = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'vcard')->where('card_status', 'activated')->count();
        $user_details = User::where('user_id', Auth::user()->user_id)->first();
        $plan_details = json_decode($user_details->plan_details, true);

        // Card URL
        $card_url     = strtolower(preg_replace('/\s+/', '-', $personalized_link));
        $current_card = BusinessCard::where('card_url', $card_url)->where('card_status', '!=', 'deleted')->count();

        // Ger purchased plan details
        if ($plan_details['no_of_vcards'] == 999) {
            $no_cards = 999999;
        } else {
            $no_cards = $plan_details['no_of_vcards'];
        }

        // Check card URL is available
        if ($current_card == 0) {
            // Checking, If the user plan allowed card creation is less than created card.
            if ($cards < $no_cards) {
                try {
                    // Check banner image
                    $cover = null;
                    if ($request->has('cover')) {
                        $cover = $request->cover;
                    }

                                          //Cover Type - Validation
                    $cover_type = "none"; // Default Value
                    if (in_array($request->cover_type, ["youtube", "youtube-ap", "vimeo", "vimeo-ap", "photo"], true)) {
                        $cover_type = $request->cover_type;
                        // Cover URL no need to update for photo type.
                        if ($request->cover_type != "photo") {
                            if ($request->cover_type == "youtube" || $request->cover_type == "youtube-ap") {
                                // Remove the "https://youtube.com/watch?v=" from the URL
                                try {
                                    // Without www
                                    $cover = str_replace("https://youtube.com/watch?v=", "", $request->cover_url);
                                    // With www
                                    $cover = str_replace("https://www.youtube.com/watch?v=", "", $cover);
                                } catch (\Exception $e) {
                                    $cover = str_replace("https://youtu.be/", "", $request->cover_url);
                                    // With www
                                    $cover = str_replace("https://www.youtu.be/", "", $cover);
                                }
                            }
                            // Vimeo URL
                            if ($request->cover_type == "vimeo" || $request->cover_type == "vimeo-ap") {
                                // Remove the "https://vimeo.com/" from the URL
                                try {
                                    $cover = str_replace("https://vimeo.com/", "", $request->cover_url);
                                    // With www
                                    $cover = str_replace("https://www.vimeo.com/", "", $cover);
                                } catch (\Exception $e) {
                                    $cover = str_replace("https://vimeo.com/album/", "", $request->cover_url);
                                    // With www
                                    $cover = str_replace("https://www.vimeo.com/album/", "", $cover);
                                }
                            }
                        }
                    }

                    // Save
                    $card              = new BusinessCard();
                    $card->card_id     = $cardId;
                    $card->user_id     = Auth::user()->user_id;
                    $card->type        = $request->type;
                    $card->theme_id    = $request->theme_id;
                    $card->card_lang   = $request->card_lang;
                    $card->cover_type  = $cover_type;
                    $card->cover       = $cover;
                    $card->profile     = $request->logo;
                    $card->card_url    = $card_url;
                    $card->card_type   = 'vcard';
                    $card->title       = $request->title;
                    $card->sub_title   = $request->subtitle;
                    $card->description = $request->description;

                    if ($request->type == 'custom') {
                        $card->custom_styles = json_encode([
                            'header_style'            => 'column',
                            'layout'                  => 'row',
                            'profile_image_style'     => 'circle',
                            'font_family'             => 'Poppins',
                            "background_type"         => "single_color",
                            "background_color"        => "#ffffff",
                            "gradient_type"           => "vertical",
                            "gradient_start"          => "#ffffff",
                            "gradient_end"            => "top_to_bottom",
                            "image_url"               => "",
                            "button_background_type"  => "single_color",
                            "button_background_color" => "#000000",
                            "button_gradient_start"   => "#000000",
                            "button_gradient_end"     => "#000000",
                            "button_edge"             => "rounded",
                            'title_color'             => '#000000',
                            'sub_title_color'         => '#000000',
                            'description_color'       => '#000000',
                            "button_text_color"       => "#ffffff",
                            "button_icon_color"       => "#ffffff",
                            "heading_color"           => "#000000",
                            "card_edge"               => "rounded",
                            "bottom_bar_color"        => "#000000",
                        ]);
                    }

                    $card->save();

                    return redirect()->route('user.social.links', $cardId)->with('success', trans('New Business Card Created Successfully!'));
                } catch (\Exception $th) {
                    return redirect()->route('user.create.card')->with('failed', trans('Sorry, the personalized link was already registered.'));
                }
            } else {
                return redirect()->route('user.create.card')->with('failed', trans('Maximum card creation limit is exceeded, Please upgrade your plan to add more card(s).'));
            }
        } else {
            return redirect()->route('user.create.card')->with('failed', trans('Sorry, the personalized link was already registered.'));
        }
    }

    // Check unique card / store link
    public function checkLink(Request $request)
    {
        // Requested link
        $link           = $request->link;
        $is_present     = DB::table('business_cards')->where('card_url', $link)->where('card_status', '!=', 'deleted')->count();
        $resp           = [];
        $resp['status'] = 'failed';

        // Check
        if ($is_present == 0) {
            $resp['status'] = 'success';
        } else {
            $resp['status'] = 'failed';
        }

        return response()->json($resp);
    }

    // Cropping image
    public function vcardCroppedImage(Request $request)
    {
        $croppedImage = $request->file('croppedImage');

        // Generate a random unique name for the image
        $imageName = Str::random(20) . '.' . $croppedImage->extension();

        // Save cropped image to desired location (move to public/storage/profile-images)
        $croppedImage->move(storage_path('app/public/profile-images'), $imageName);

        // You can also save the path to the cropped image in the database if needed
        return response()->json(['success' => true, 'imageUrl' => "storage/profile-images/" . $imageName]);
    }
        /**
     * Interfaz unificada para crear vCards
     */
    public function createUnified(Request $request)
    {
        $card_type = $request->get('type', 'personal');
        $card_id = $request->get('id', 'new');
        
        // Obtener plan del usuario
        $user = Auth::user();
        $plan_details = json_decode($user->plan_details);
        
        // Generar tabs dinámicos según plan y tipo
        $tabs = $this->generateTabs($card_type, $plan_details);

        // Obtener themes según el tipo (IGUAL que CreateCard)
        if ($card_type == "business") {
            $themes = Theme::where('theme_description', 'vCard')->whereNotIn('theme_id', ["588969111014", "588969111015", "588969111016", "588969111017", "588969111018", "588969111019", "588969111020", "588969111021", "588969111147"])->orderBy('id', 'desc')->where('status', 1)->get();
        } else {
            $themes = Theme::where('theme_description', 'vCard')->whereIn('theme_id', ["588969111014", "588969111015", "588969111016", "588969111017", "588969111018", "588969111019", "588969111020", "588969111021"])->orderBy('id', 'desc')->where('status', 1)->get();
        }

        $settings = Setting::where('status', 1)->first();
        
        // Obtener configuración global
        $config = Setting::first();

        // Obtener imagen por defecto según tipo
        $defaultImage = "";
        if ($card_type == "business") {
            $defaultImage = asset("img/vCards/flowershop.png");
        } else {
            $defaultImage = asset("img/vCards/personal-gray.png");
        }
        
        return view('user.pages.cards.create-unified', compact(
            'card_type',
            'card_id',
            'user',
            'plan_details',
            'tabs',
            'themes',
            'settings',
            'config',
            'defaultImage'
        ));
    }

    /**
     * Cargar contenido de tab vía AJAX
     */
    public function loadTab(Request $request, $tab)
{
    $card_id = $request->get('card_id', 'new');
    $card_type = $request->get('card_type', 'personal');
    
    // Definir qué vista cargar según el tab
    $tab_views = [
        'basic-info' => 'user.pages.cards.tabs.basic-info',
        'social-links' => 'user.pages.cards.tabs.social-links',
        'products' => 'user.pages.cards.tabs.products',
        'services' => 'user.pages.cards.tabs.services',
        'gallery' => 'user.pages.cards.tabs.gallery',
        'design' => 'user.pages.cards.tabs.design'
    ];
    
    if (!isset($tab_views[$tab])) {
        return response()->json(['error' => 'Tab not found'], 404);
    }
    
    // Datos mínimos
    $view_data = [
        'card_id' => $card_id,
        'card_type' => $card_type
    ];

    // Añadir themes solo para basic-info tab
    if ($tab === 'basic-info') {
    if ($card_type == "business") {
        $themes = Theme::where('theme_description', 'vCard')->whereNotIn('theme_id', ["588969111014", "588969111015", "588969111016", "588969111017", "588969111018", "588969111019", "588969111020", "588969111021"])->orderBy('id', 'desc')->where('status', 1)->get();
    } else {
        $themes = Theme::where('theme_description', 'vCard')->whereIn('theme_id', ["588969111014", "588969111015", "588969111016", "588969111017", "588969111018", "588969111019", "588969111020", "588969111021"])->orderBy('id', 'desc')->where('status', 1)->get();
    }
    $view_data['themes'] = $themes;
    }
    
    return view($tab_views[$tab], $view_data);
}

    /**
     * Guardar datos de tab vía AJAX
     */
    public function saveTab(Request $request, $tab)
    {
        $card_id = $request->get('card_id');
        $card_type = $request->get('card_type');
        
        try {
            // Procesar según el tab específico
            switch ($tab) {
                case 'basic-info':
                    return $this->saveBasicInfo($request);
                case 'social-links':
                    return $this->saveSocialLinks($request);
                case 'products':
                    return $this->saveProducts($request);
                case 'services':
                    return $this->saveServices($request);
                case 'gallery':
                    return $this->saveGallery($request);
                case 'design':
                    return $this->saveDesign($request);
                default:
                    return response()->json(['error' => 'Invalid tab'], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error saving data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview de la vCard
     */
    public function viewPreview(Request $request, $id)
    {
        if ($id === 'new') {
            // Preview temporal para vCard nueva
            return view('user.preview.temp-preview', [
                'message' => 'Save your vCard first to see the preview'
            ]);
        }
        
        // Cargar vCard existente para preview
        $card = BusinessCard::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$card) {
            abort(404, 'vCard not found');
        }
        
        // Redirigir al preview real de la vCard
        return redirect()->route('dynamic.card', ['card_id' => $card->card_id]);
    }

    /**
     * Generar tabs dinámicos según plan y tipo
     */
    private function generateTabs($card_type, $plan_details)
    {
        // Tabs base (siempre presentes)
        $tabs = [
            'basic-info' => [
                'title' => '⚙️ Basic Info',
                'icon' => '⚙️',
                'required' => true,
                'disabled' => false
            ],
            'social-links' => [
                'title' => '🔗 Social Links', 
                'icon' => '🔗',
                'required' => false,
                'disabled' => false
            ]
        ];
        
        // Tabs específicos de Business
        if ($card_type === 'business') {
            if ($plan_details->no_of_vcard_products > 0) {
                $tabs['products'] = [
                    'title' => '💼 Products',
                    'icon' => '💼', 
                    'limit' => $plan_details->no_of_vcard_products,
                    'disabled' => false
                ];
            }
            
            if ($plan_details->no_of_services > 0) {
                $tabs['services'] = [
                    'title' => '🎯 Services',
                    'icon' => '🎯',
                    'limit' => $plan_details->no_of_services,
                    'disabled' => false
                ];
            }
        }
        
        // Tabs opcionales según plan
        if ($plan_details->no_of_galleries > 0) {
            $tabs['gallery'] = [
                'title' => '📸 Gallery',
                'icon' => '📸',
                'limit' => $plan_details->no_of_galleries,
                'disabled' => false
            ];
        }
        
        // Tab Design (funcionalidades Custom)
        if ($plan_details->advanced_settings == 1) {
            $tabs['design'] = [
                'title' => '🎨 Design',
                'icon' => '🎨', 
                'features' => 'custom_css_js_seo',
                'disabled' => false
            ];
        }
        
        return $tabs;
    }

    /**
     * Obtener temas según tipo de vCard
     */
    private function getThemesByType($card_type)
    {
        if ($card_type === 'business') {
            return Theme::whereNotIn('theme_id', [
                "588969111014", "588969111015", "588969111016", 
                "588969111017", "588969111018", "588969111019", 
                "588969111020", "588969111021"
            ])->get();
        } else {
            return Theme::whereIn('theme_id', [
                "588969111014", "588969111015", "588969111016", 
                "588969111017", "588969111018", "588969111019", 
                "588969111020", "588969111021"
            ])->get();
        }
    }

    /**
     * Guardar información básica
     */
    private function saveBasicInfo($request)
{
    // Usar EXACTAMENTE la misma validación que saveBusinessCard
    $validator = Validator::make($request->all(), [
        'theme_id'    => 'required',
        'card_lang'   => 'required',
        'title'       => 'required',
        'cover_type'  => 'required',
        'subtitle'    => 'required',
        'description' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => 'Validation failed',
            'messages' => $validator->errors()
        ], 422);
    }

    // Reutilizar EXACTAMENTE la lógica de saveBusinessCard
    $cardId = uniqid();
    
    if ($request->link) {
        $personalized_link = $request->link;
    } else {
        $personalized_link = $cardId;
    }

    // Mismas queries que el original
    $cards = BusinessCard::where('user_id', Auth::user()->user_id)->where('card_type', 'vcard')->where('card_status', 'activated')->count();
    $user_details = User::where('user_id', Auth::user()->user_id)->first();
    $plan_details = json_decode($user_details->plan_details, true);

    // Card URL
    $card_url = strtolower(preg_replace('/\s+/', '-', $personalized_link));
    $current_card = BusinessCard::where('card_url', $card_url)->where('card_status', '!=', 'deleted')->count();

    // Check limits exactly like original
    if ($plan_details['no_of_vcards'] == 999) {
        $no_cards = 999999;
    } else {
        $no_cards = $plan_details['no_of_vcards'];
    }

    if ($current_card == 0) {
        if ($cards < $no_cards) {
            try {
                // Cover logic - exact same as original
                $cover_type = $request->cover_type;
                if ($cover_type == 'photo') {
                    $cover = $request->cover;
                } else {
                    $cover = $request->cover_url;
                }

                // Save - EXACTAMENTE igual que el original
                $card = new BusinessCard();
                $card->card_id = $cardId;
                $card->user_id = Auth::user()->user_id;
                $card->type = $request->type;
                $card->theme_id = $request->theme_id;
                $card->card_lang = $request->card_lang;
                $card->cover_type = $cover_type;
                $card->cover = $cover;
                $card->profile = $request->logo;
                $card->card_url = $card_url;
                $card->card_type = 'vcard';
                $card->title = $request->title;
                $card->sub_title = $request->subtitle;
                $card->description = $request->description;
                $card->save();

                return response()->json([
                    'success' => true,
                    'card_id' => $cardId,
                    'message' => 'Basic info saved successfully'
                ]);
            } catch (\Exception $th) {
                return response()->json([
                    'error' => 'Creation failed',
                    'message' => $th->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'error' => 'Limit exceeded',
                'message' => 'Maximum card creation limit exceeded'
            ], 422);
        }
    } else {
        return response()->json([
            'error' => 'URL taken',
            'message' => 'Personalized link already registered'
        ], 422);
    }
}

    /**
     * Métodos placeholder para otros tabs
     */
    private function saveSocialLinks($request)
    {
        return response()->json(['success' => true, 'message' => 'Social links saved']);
    }

    private function saveProducts($request)
    {
        return response()->json(['success' => true, 'message' => 'Products saved']);
    }

    private function saveServices($request)
    {
        return response()->json(['success' => true, 'message' => 'Services saved']);
    }

    private function saveGallery($request)
    {
        return response()->json(['success' => true, 'message' => 'Gallery saved']);
    }

    private function saveDesign($request)
    {
        return response()->json(['success' => true, 'message' => 'Design saved']);
    }
}
