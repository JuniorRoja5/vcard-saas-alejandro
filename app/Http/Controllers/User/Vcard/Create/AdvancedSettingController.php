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

use App\Setting;
use App\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    // Advanced settings
    public function advancedSetting(Request $request, $id)
    {
        // Queries
        $settings = Setting::where('status', 1)->first();

        $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        $plan_details = json_decode($plan->plan_details);

        if ($plan_details->advanced_settings == 1) {
            return view('user.pages.cards.advanced-settings', compact('plan_details', 'settings'));
        } else {
            return redirect()->route('user.cards')->with('success', trans('Your virtual business card is updated!'));
        }
    }

    // Save Advanced settings
    public function saveAdvancedSetting(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
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

                // Unique file name
                $fileName = uniqid() . '.png';

                // Store the file in storage/app/public/vcard/favicons
                Storage::disk('public')->putFileAs('vcard/favicons', $favicon, $fileName);

                // Access the file via public URL
                $favicon = 'storage/vcard/favicons/' . $fileName;
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

            // Check pwa enable/disable
            if ($request->is_enable_pwa == "on") {
                $request->is_enable_pwa = 1;
            } else {
                $request->is_enable_pwa = 0;
            }

            // Update
            BusinessCard::where('card_id', $id)->update([
                'password' => $request->password,
                'custom_css' => $request->custom_css,
                'custom_js' => $request->custom_js,
                'seo_configurations' => json_encode($business_card->seo_configurations),
                'is_enable_pwa' => $request->is_enable_pwa,
            ]);

            return redirect()->route('user.cards')->with('success', trans('Your virtual business card is updated!'));
        }
    }
}
