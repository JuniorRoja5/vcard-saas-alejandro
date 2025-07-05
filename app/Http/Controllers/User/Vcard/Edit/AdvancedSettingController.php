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

namespace App\Http\Controllers\User\Vcard\Edit;

use App\Setting;
use App\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdvancedSettingController extends Controller
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

    // Edit Advanced settings
    public function editAdvancedSetting(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();
        $settings = Setting::where('status', 1)->first();

        // Check business card
        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
            if($plan_details->password_protected == 1 || $plan_details->advanced_settings == 1) {
                return view('user.pages.edit-cards.edit-advanced-settings', compact('plan_details', 'business_card', 'settings'));
            } else {
                return redirect()->route('user.cards')->with('success', trans('Your virtual business card is ready.'));
            }
        }
    }

    // Update Advanced settings
    public function updateAdvancedSetting(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
            // Set password
            $password = $request->password;
            if ($request->password_protected == "on") {
                $password = null;
            }

            // Check meta title
            $metaTitle = $request->meta_title;
            if (strlen($metaTitle) > 70) {
                $metaTitle = substr($metaTitle, 0, 70);
            }

            // Check meta description
            $metaDescription = $request->meta_description;
            if (strlen($metaDescription) > 160) {
                $metaDescription = substr($metaDescription, 0, 160);
            }

            // Check meta keywords
            $metaKeywords = $request->meta_keywords;
            if (strlen($metaKeywords) > 70) {
                $metaKeywords = substr($metaKeywords, 0, 70);
            }

            // Check favicon 
            if ($request->hasFile('favicon')) {
                // Save favicon image
                $favicon = $request->file('favicon');

                // Upload favicon image
                $favicon->move(public_path('storage/store/favicon'), $request->store_id . '.png');

                $favicon = "storage/store/favicons/" . $request->store_id . '.png';
            } else {
                $favicon = null;
            }

            // Update seo configurations
            $business_card->seo_configurations = [
                'favicon' => $favicon,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription,
                'meta_keywords' => $metaKeywords,
            ];

            // Update
            BusinessCard::where('card_id', $id)->update([
                'password' => $password,
                'custom_css' => $request->custom_css,
                'custom_js' => $request->custom_js,
                'seo_configurations' => json_encode($business_card->seo_configurations),
            ]);

            return redirect()->route('user.cards')->with('success', trans('Your virtual business card is updated!'));
        }
    }
}
