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

namespace App\Http\Controllers;

use App\Plan;
use App\Setting;
use App\Visitor;
use App\Category;
use App\Currency;
use Carbon\Carbon;
use App\Testimonial;
use App\StoreProduct;
use App\BusinessField;
use App\StoreCategory;
use App\StoreBusinessHour;
use Jenssegers\Agent\Agent;
use App\CardAppointmentTime;
use Illuminate\Http\Request;
use App\Classes\ServiceWorker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Artesaos\SEOTools\Facades\OpenGraph;

class CustomDomainController extends Controller
{
    // Custom domain
    public function customDomain(Request $request)
    {
        // Queries
        $config = DB::table('config')->get();
        $plans = Plan::where('status', 1)->where('is_private', '0')->get();

        // Get the current domain or customdomain from the request
        $host = $request->getHost();

        // Get business details
        $card_details = DB::table('business_cards')->where('custom_domain', $host)->where('card_status', 'activated')->first();
        $currentUser = 0;

        // Check storage folder
        if (!File::isDirectory('storage')) {
            File::link(storage_path('app/public'), public_path('storage'));
        }

        if (isset($card_details)) {
            $currentUser = DB::table('users')->where('user_id', $card_details->user_id)->where('status', 1)->where('plan_validity', '>=', Carbon::now())->count();
        }

        // Check if the host is the main domain (karthikeyan.com)
        if ($currentUser == 1 && $host === $card_details->custom_domain) {
            // Check whatsapp number exists
            $whatsAppNumberExists = BusinessField::where('card_id', $card_details->card_id)->where('type', 'wa')->exists();

            // Save visitor
            $clientIP = \Request::getClientIp(true);

            $agent     = new Agent();
            $userAgent = $request->header('user_agent');
            $agent->setUserAgent($userAgent);

            // Device
            $device = $agent->device();
            if ($device == "" || $device == "0") {
                $device = "Others";
            }

            // Language
            $language = "en";
            if ($agent->languages()) {
                $language = $agent->languages()[0];
            }

            $visitor             = new Visitor();
            $visitor->card_id    = $card_details->card_url;
            $visitor->type       = $card_details->card_type;
            $visitor->ip_address = $clientIP;
            $visitor->platform   = $agent->platform();
            $visitor->device     = $agent->device();
            $visitor->language   = $language;
            $visitor->user_agent = $userAgent;
            $visitor->save();

            if (isset($card_details)) {
                if ($card_details->card_type == "store") {
                    $enquiry_button = '#';

                    $business_card_details = DB::table('business_cards')->where('business_cards.card_id', $card_details->card_id)
                        ->join('users', 'business_cards.user_id', '=', 'users.user_id')
                        ->join('themes', 'business_cards.theme_id', '=', 'themes.theme_id')
                        ->select('business_cards.*', 'users.plan_details', 'themes.theme_code')
                        ->first();

                    if ($business_card_details) {
                        $products = StoreProduct::join('store_categories', 'store_products.category_id', '=', 'store_categories.category_id')
                        ->where('store_products.card_id', $card_details->card_id)
                        ->where('store_categories.user_id', $business_card_details->user_id)
                        ->where('store_products.product_status', 'instock')
                        ->where('store_categories.status', 1)
                        ->select(
                            'store_products.id',
                            'store_products.product_id',
                            'store_products.product_name',
                            'store_products.product_image',
                            'store_products.product_short_description',
                            'store_products.regular_price',
                            'store_products.sales_price',
                            'store_products.badge',
                            'store_products.product_status',
                            'store_categories.category_name',
                            'store_categories.thumbnail',
                            'store_categories.category_id'
                        )
                        ->groupBy(
                            'store_products.id',
                            'store_products.product_id',
                            'store_products.product_name',
                            'store_products.product_image',
                            'store_products.product_short_description',
                            'store_products.regular_price',
                            'store_products.sales_price',
                            'store_products.badge',
                            'store_products.product_status',
                            'store_categories.category_name',
                            'store_categories.thumbnail',
                            'store_categories.category_id'
                        );

                        // Filter: Price Range
                        if ($request->filled('min') && $request->filled('max')) {
                            $min = (float) $request->input('min');
                            $max = (float) $request->input('max');
                            $products->whereBetween('store_products.sales_price', [$min, $max]);
                        }

                        // Filter: Search Query
                        if ($request->filled('query')) {
                            $products->where('store_products.product_name', 'like', '%' . $request->get('query') . '%');
                        }

                        // Filter: Category
                        if ($request->filled('category')) {
                            $products->where('store_categories.category_name', ucfirst($request->get('category')));
                        }

                        // Sorting
                        switch ($request->get('sort')) {
                            case 'price_asc':
                                $products->orderBy('store_products.sales_price', 'asc');
                                break;
                            case 'price_desc':
                                $products->orderBy('store_products.sales_price', 'desc');
                                break;
                            case 'name_asc':
                                $products->orderBy('store_products.product_name', 'asc');
                                break;
                            case 'name_desc':
                                $products->orderBy('store_products.product_name', 'desc');
                                break;
                            default:
                                $products->orderBy('store_products.id', 'desc');
                        }

                        // Delivery Options
                        $deliveryOptions = json_decode($business_card_details->delivery_options);

                        // Business Hours
                        $businessHours = StoreBusinessHour::where('store_id', $business_card_details->card_id)->first();

                        // Paginate (consistent)
                        $products = $products->paginate($request->filled('category') ? 9 : 8)->withQueryString();

                        // Get categories
                        $getCategories = DB::table('store_products')->select('category_id')->groupBy('category_id')->where('card_id', $card_details->card_id)->where('user_id', $business_card_details->user_id);
                        $categories    = StoreCategory::where('store_id', $card_details->card_id)->get();

                        $settings = Setting::where('status', 1)->first();
                        $config   = DB::table('config')->get();

                        // Check meta title, description, keywords is enter to customer
                        if(isset($business_card_details->seo_configurations) && json_decode($business_card_details->seo_configurations)->meta_title != null) {
                            SEOTools::setTitle(json_decode($business_card_details->seo_configurations)->meta_title);
                            SEOMeta::setTitle(json_decode($business_card_details->seo_configurations)->meta_title);
                            OpenGraph::setTitle(json_decode($business_card_details->seo_configurations)->meta_title);
                            JsonLd::setTitle(json_decode($business_card_details->seo_configurations)->meta_title);
                            SEOMeta::addMeta('article:section', json_decode($business_card_details->seo_configurations)->meta_title, 'property');
                        } else {
                            SEOTools::setTitle($business_card_details->title);
                            SEOMeta::setTitle($business_card_details->title);
                            OpenGraph::setTitle($business_card_details->title);
                            JsonLd::setTitle($business_card_details->title);
                            SEOMeta::addMeta('article:section', $business_card_details->title, 'property');
                        }

                        if(isset($business_card_details->seo_configurations) && json_decode($business_card_details->seo_configurations)->meta_description != null) {
                            SEOTools::setDescription(json_decode($business_card_details->seo_configurations)->meta_description);
                            SEOMeta::setDescription(json_decode($business_card_details->seo_configurations)->meta_description);
                            OpenGraph::setDescription(json_decode($business_card_details->seo_configurations)->meta_description);
                            JsonLd::setDescription(json_decode($business_card_details->seo_configurations)->meta_description);
                        } else {
                            SEOTools::setDescription($business_card_details->sub_title);
                            SEOMeta::setDescription($business_card_details->sub_title);
                            OpenGraph::setDescription($business_card_details->sub_title);
                            JsonLd::setDescription($business_card_details->sub_title);
                        }

                        if(isset($business_card_details->seo_configurations) && json_decode($business_card_details->seo_configurations)->meta_keywords != null) {
                            SEOMeta::addKeyword(json_decode($business_card_details->seo_configurations)->meta_keywords);
                        } else {
                            SEOMeta::addKeyword(["'" . $business_card_details->title . "'", "'" . $business_card_details->title . " vcard online'"]);
                        }

                        // Check favicon
                        if(isset($business_card_details->seo_configurations) && json_decode($business_card_details->seo_configurations)->favicon != null) {
                            SEOTools::addImages([url(json_decode($business_card_details->seo_configurations)->favicon)]);
                            OpenGraph::addImage([url(json_decode($business_card_details->seo_configurations)->favicon)]);
                            JsonLd::addImage([url(json_decode($business_card_details->seo_configurations)->favicon)]);
                        } else {
                            SEOTools::addImages([url($business_card_details->profile)]);
                            OpenGraph::addImage([url($business_card_details->profile)]);
                            JsonLd::addImage([url($business_card_details->profile)]);
                        }

                        OpenGraph::setUrl(url($business_card_details->card_url));

                        // PWA
                        $icons = [
                            '512x512' => [
                                'path'    => url($business_card_details->profile),
                                'purpose' => 'any',
                            ],
                        ];

                        $splash = [
                            '640x1136'  => url($business_card_details->profile),
                            '750x1334'  => url($business_card_details->profile),
                            '828x1792'  => url($business_card_details->profile),
                            '1125x2436' => url($business_card_details->profile),
                            '1242x2208' => url($business_card_details->profile),
                            '1242x2688' => url($business_card_details->profile),
                            '1536x2048' => url($business_card_details->profile),
                            '1668x2224' => url($business_card_details->profile),
                            '1668x2388' => url($business_card_details->profile),
                            '2048x2732' => url($business_card_details->profile),
                        ];

                        $shortcuts = [
                            [
                                'name'        => $business_card_details->title,
                                'description' => $business_card_details->sub_title,
                                'url'         => asset($business_card_details->card_url),
                                'icons'       => [
                                    "src"     => url($business_card_details->profile),
                                    "purpose" => "any",
                                ],
                            ],
                        ];

                        $fill = [
                            "name"        => $business_card_details->title,
                            "short_name"  => $business_card_details->title,
                            "start_url"   => asset($business_card_details->card_url),
                            "theme_color" => "#ffffff",
                            "icons"       => $icons,
                            "splash"      => $splash,
                            "shortcuts"   => $shortcuts,
                        ];

                        $out = $this->generateNew($fill);

                        Storage::disk('public')->put("manifest/" . $business_card_details->card_id . '.json', json_encode($out));

                        $manifest = url("storage/manifest/" . $business_card_details->card_id . '.json');

                        // Generate service worker
                        $generateServiceWorker = new ServiceWorker();
                        $generateServiceWorker->generateServiceWorker($business_card_details->card_id, $business_card_details->card_url);

                        $plan_details  = json_decode($business_card_details->plan_details, true);
                        $store_details = json_decode($business_card_details->description, true);

                        if ($store_details['whatsapp_no'] != null) {
                            $enquiry_button = $store_details['whatsapp_no'];
                        }

                        $whatsapp_msg = $store_details['whatsapp_msg'];
                        $currency     = $store_details['currency'];

                        // Get currency symbol
                        $currency = Currency::where('iso_code', $currency)->first();
                        $currency = $currency->symbol;

                        $url           = URL::to('/');
                        $business_name = $card_details->title;
                        $profile       = URL::to('/') . "/" . $business_card_details->cover;

                        $shareContent = $config[30]->config_value;
                        $shareContent = str_replace("{ business_name }", $business_name, $shareContent);
                        $shareContent = str_replace("{ business_url }", $url, $shareContent);
                        $shareContent = str_replace("{ appName }", $config[0]->config_value, $shareContent);

                        // If branding enabled, then show app name.
                        if ($plan_details['hide_branding'] == "1") {
                            $shareContent = str_replace("{ appName }", $business_name, $shareContent);
                        } else {
                            $shareContent = str_replace("{ appName }", $config[0]->config_value, $shareContent);
                        }

                        $url          = urlencode($url);
                        $shareContent = urlencode($shareContent);

                        Session::put('locale', strtolower($business_card_details->card_lang));
                        app()->setLocale(Session::get('locale'));

                        $qr_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=" . $url;

                        $shareComponent['facebook'] = "https://www.facebook.com/sharer/sharer.php?u=$url&quote=$shareContent";
                        $shareComponent['twitter']  = "https://twitter.com/intent/tweet?text=$shareContent";
                        $shareComponent['linkedin'] = "https://www.linkedin.com/shareArticle?mini=true&url=$url";
                        $shareComponent['telegram'] = "https://telegram.me/share/url?text=$shareContent&url=$url";
                        $shareComponent['whatsapp'] = "https://api.whatsapp.com/send/?phone&text=$shareContent";

                        $datas = compact('card_details', 'plan_details', 'store_details', 'categories', 'business_card_details', 'products', 'settings', 'shareComponent', 'shareContent', 'config', 'enquiry_button', 'whatsapp_msg', 'currency', 'manifest', 'whatsAppNumberExists', 'deliveryOptions', 'businessHours');
                        return view('templates.store.' . $business_card_details->theme_code . '.index', $datas);
                    } else {
                        return redirect()->route('user.edit.card', $card_details->id)->with('failed', trans('Please fill out the basic business details.'));
                    }
                } else {
                    $enquiry_button = "#";

                    $business_card_details = DB::table('business_cards')->where('business_cards.card_id', $card_details->card_id)
                        ->join('users', 'business_cards.user_id', '=', 'users.user_id')
                        ->join('themes', 'business_cards.theme_id', '=', 'themes.theme_id')
                        ->select('business_cards.*', 'users.plan_details', 'themes.theme_code')
                        ->first();

                    if ($business_card_details) {
                        $feature_details   = DB::table('business_fields')->where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();
                        $service_details   = DB::table('services')->where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();
                        $product_details   = DB::table('vcard_products')->where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();
                        $galleries_details = DB::table('galleries')->where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();
                        $testimonials      = Testimonial::where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();
                        $payment_details   = DB::table('payments')->where('card_id', $card_details->card_id)->get();
                        $business_hours    = DB::table('business_hours')->where('card_id', $card_details->card_id)->first();
                        $make_enquiry      = DB::table('business_fields')->where('card_id', $card_details->card_id)->where('type', 'wa')->first();
                        $iframes           = DB::table('business_fields')->where('type', 'iframe')->where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();
                        $customTexts       = DB::table('business_fields')->where('type', 'text')->where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();

                        // Appointment slots for the card
                        $appointmentSlots = CardAppointmentTime::where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();

                        // Initialize the time slots array
                        $appointmentEnabled = false;
                        $appointment_slots  = [
                            'monday'    => [],
                            'tuesday'   => [],
                            'wednesday' => [],
                            'thursday'  => [],
                            'friday'    => [],
                            'saturday'  => [],
                            'sunday'    => [],
                        ];

                        // Iterate through the appointment slots and categorize them by day
                        foreach ($appointmentSlots as $slot) {
                                                            // Assuming your `CardAppointmentTime` model has a `day` attribute and a `time` attribute
                            $day  = strtolower($slot->day); // Convert to lowercase to match array keys
                            $time = $slot->time_slots;      // Assuming this contains the time range string like "16:00 - 17:00"

                            // Check if the day exists in the time_slots array
                            if (array_key_exists($day, $appointment_slots)) {
                                $appointment_slots[$day][] = $time; // Add the time to the appropriate day
                                                                    // Get price
                                $appointment_slots[$day][] = $slot->price;
                            }

                            $appointmentEnabled = true;
                        }

                        $appointment_slots = json_encode($appointment_slots); // Convert the array to JSON

                        if ($make_enquiry != null) {
                            $enquiry_button = $make_enquiry->content;
                        }

                        $settings = Setting::where('status', 1)->first();
                        $config   = DB::table('config')->get();

                        // Check meta title, description, keywords is enter to customer
                        if(isset($business_card_details->seo_configurations) && json_decode($business_card_details->seo_configurations)->meta_title != null) {
                            SEOTools::setTitle(json_decode($business_card_details->seo_configurations)->meta_title);
                            SEOMeta::setTitle(json_decode($business_card_details->seo_configurations)->meta_title);
                            OpenGraph::setTitle(json_decode($business_card_details->seo_configurations)->meta_title);
                            JsonLd::setTitle(json_decode($business_card_details->seo_configurations)->meta_title);
                            SEOMeta::addMeta('article:section', json_decode($business_card_details->seo_configurations)->meta_title, 'property');
                        } else {
                            SEOTools::setTitle($business_card_details->title);
                            SEOMeta::setTitle($business_card_details->title);
                            OpenGraph::setTitle($business_card_details->title);
                            JsonLd::setTitle($business_card_details->title);
                            SEOMeta::addMeta('article:section', $business_card_details->title, 'property');
                        }

                        if(isset($business_card_details->seo_configurations) && json_decode($business_card_details->seo_configurations)->meta_description != null) {
                            SEOTools::setDescription(json_decode($business_card_details->seo_configurations)->meta_description);
                            SEOMeta::setDescription(json_decode($business_card_details->seo_configurations)->meta_description);
                            OpenGraph::setDescription(json_decode($business_card_details->seo_configurations)->meta_description);
                            JsonLd::setDescription(json_decode($business_card_details->seo_configurations)->meta_description);
                        } else {
                            SEOTools::setDescription($business_card_details->sub_title);
                            SEOMeta::setDescription($business_card_details->sub_title);
                            OpenGraph::setDescription($business_card_details->sub_title);
                            JsonLd::setDescription($business_card_details->sub_title);
                        }

                        if(isset($business_card_details->seo_configurations) && json_decode($business_card_details->seo_configurations)->meta_keywords != null) {
                            SEOMeta::addKeyword(json_decode($business_card_details->seo_configurations)->meta_keywords);
                        } else {
                            SEOMeta::addKeyword(["'" . $business_card_details->title . "'", "'" . $business_card_details->title . " vcard online'"]);
                        }

                        // Check favicon
                        if(isset($business_card_details->seo_configurations) && json_decode($business_card_details->seo_configurations)->favicon != null) {
                            SEOTools::addImages([url(json_decode($business_card_details->seo_configurations)->favicon)]);
                            OpenGraph::addImage([url(json_decode($business_card_details->seo_configurations)->favicon)]);
                            JsonLd::addImage([url(json_decode($business_card_details->seo_configurations)->favicon)]);
                        } else {
                            SEOTools::addImages([url($business_card_details->profile)]);
                            OpenGraph::addImage([url($business_card_details->profile)]);
                            JsonLd::addImage([url($business_card_details->profile)]);
                        }

                        OpenGraph::setUrl(url($business_card_details->card_url));

                        // PWA
                        $icons = [
                            '512x512' => [
                                'path'    => url($business_card_details->profile),
                                'purpose' => 'any',
                            ],
                        ];

                        $splash = [
                            '640x1136'  => url($business_card_details->profile),
                            '750x1334'  => url($business_card_details->profile),
                            '828x1792'  => url($business_card_details->profile),
                            '1125x2436' => url($business_card_details->profile),
                            '1242x2208' => url($business_card_details->profile),
                            '1242x2688' => url($business_card_details->profile),
                            '1536x2048' => url($business_card_details->profile),
                            '1668x2224' => url($business_card_details->profile),
                            '1668x2388' => url($business_card_details->profile),
                            '2048x2732' => url($business_card_details->profile),
                        ];

                        $shortcuts = [
                            [
                                'name'        => $business_card_details->title,
                                'description' => $business_card_details->sub_title,
                                'url'         => asset($business_card_details->card_url),
                                'icons'       => [
                                    "src"     => url($business_card_details->profile),
                                    "purpose" => "any",
                                ],
                            ],
                        ];

                        $fill = [
                            "name"        => $business_card_details->title,
                            "short_name"  => $business_card_details->title,
                            "start_url"   => asset($business_card_details->card_url),
                            "theme_color" => "#ffffff",
                            "icons"       => $icons,
                            "splash"      => $splash,
                            "shortcuts"   => $shortcuts,
                        ];

                        $out = $this->generateNew($fill);

                        Storage::disk('public')->put("manifest/" . $business_card_details->card_id . '.json', json_encode($out));

                        $manifest = url("storage/manifest/" . $business_card_details->card_id . '.json');

                        // Generate service worker
                        $generateServiceWorker = new ServiceWorker();
                        $generateServiceWorker->generateServiceWorker($business_card_details->card_id, $business_card_details->card_url);

                        $plan_details = json_decode($business_card_details->plan_details, true);

                        $url           = URL::to('/');
                        $business_name = $card_details->title;
                        $profile       = URL::to('/') . "/" . $business_card_details->cover;

                        $shareContent = $config[30]->config_value;
                        $shareContent = str_replace("{ business_name }", $business_name, $shareContent);
                        $shareContent = str_replace("{ business_url }", $url, $shareContent);

                        // If branding enabled, then show app name.
                        if ($plan_details['hide_branding'] == "1") {
                            $shareContent = str_replace("{ appName }", $business_name, $shareContent);
                        } else {
                            $shareContent = str_replace("{ appName }", $config[0]->config_value, $shareContent);
                        }

                        $url          = urlencode($url);
                        $shareContent = urlencode($shareContent);

                        Session::put('locale', strtolower($business_card_details->card_lang));
                        app()->setLocale(Session::get('locale'));

                        $qr_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=" . $url;

                        $shareComponent['facebook'] = "https://www.facebook.com/sharer/sharer.php?u=$url&quote=$shareContent";
                        $shareComponent['twitter']  = "https://twitter.com/intent/tweet?text=$shareContent";
                        $shareComponent['linkedin'] = "https://www.linkedin.com/shareArticle?mini=true&url=$url";
                        $shareComponent['telegram'] = "https://telegram.me/share/url?text=$shareContent&url=$url";
                        $shareComponent['whatsapp'] = "https://api.whatsapp.com/send/?phone&text=$shareContent";

                        // Datas
                        $datas = compact('card_details', 'plan_details', 'business_card_details', 'feature_details', 'service_details', 'product_details', 'galleries_details', 'testimonials', 'payment_details', 'appointmentEnabled', 'appointment_slots', 'business_hours', 'settings', 'shareComponent', 'shareContent', 'config', 'enquiry_button', 'iframes', 'customTexts', 'manifest', 'whatsAppNumberExists');

                        return view('templates.' . $business_card_details->theme_code, $datas);
                    } else {
                        return redirect()->route('user.edit.card', $card_details->id)->with('failed', trans('Please fill out the basic business details.'));
                    }
                }
            } else {
                http_response_code(404);
                return view('errors.404');
            }
        }

        // If no vCard is found, return a 404 page
        if (!$card_details) {
            abort(404, trans('Vcard not found.'));
        }

        // Display the vCard for the custom domain
        return view('vcard.show', compact('vcard'));
    }

    public function generateNew($fill)
    {
        $basicManifest = [
            'name' => $fill['name'],
            'short_name' => $fill['short_name'],
            'start_url' => $fill['start_url'],
            'background_color' => '#ffffff',
            'theme_color' => '#000000',
            'display' => 'standalone',
            'status_bar' => "black",
            'splash' => $fill['splash']
        ];

        foreach ($fill['icons'] as $size => $file) {
            $fileInfo = pathinfo($file['path']);
            $basicManifest['icons'][] = [
                'src' => $file['path'],
                'type' => 'image/' . $fileInfo['extension'],
                'sizes' => $size,
                'purpose' => $file['purpose']
            ];
        }

        if ($fill['shortcuts']) {
            foreach ($fill['shortcuts'] as $shortcut) {

                if (array_key_exists("icons", $shortcut)) {
                    $fileInfo = pathinfo($shortcut['icons']['src']);
                    $icon = [
                        'src' => $shortcut['icons']['src'],
                        'type' => 'image/' . $fileInfo['extension'],
                        'sizes' => $size,
                        'purpose' => $shortcut['icons']['purpose']
                    ];
                } else {
                    $icon = [];
                }

                $basicManifest['shortcuts'][] = [
                    'name' => trans($shortcut['name']),
                    'description' => trans($shortcut['description']),
                    'url' => $shortcut['url'],
                    'icons' => [
                        $icon
                    ]
                ];
            }
        }
        return $basicManifest;
    }
}
