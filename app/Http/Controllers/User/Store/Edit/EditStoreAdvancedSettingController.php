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

namespace App\Http\Controllers\User\Store\Edit;

use App\User;
use App\Setting;
use PSpell\Config;
use App\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EditStoreAdvancedSettingController extends Controller
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

    // Edit Store Advanced Settings
    public function editStoreAdvancedSetting(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.stores')->with('failed', trans('Store not found!'));
        } else {
            // Queries
            $settings = Setting::where('status', 1)->first();
            $config = DB::table('config')->get();

            // Plan details
            $plan = User::where('user_id', Auth::user()->user_id)->where('status', 1)->first();
            $plan_details = json_decode($plan->plan_details);

            return view('user.pages.edit-store.edit-advanced-settings', compact('business_card', 'plan_details', 'settings', 'config'));
        }
    }

    public function updateStoreAdvancedSetting(Request $request)
    { 
        // Queries
        $business_card = BusinessCard::where('card_id', $request->store_id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.stores')->with('failed', trans('Store not found!'));
        } else {
            // Check pwa enable/disable
            if ($request->is_enable_pwa == "on") {
                $request->is_enable_pwa = 1;
            } else {
                $request->is_enable_pwa = 0;
            }

            // Update store advanced setting
            $business_card->is_enable_pwa = $request->is_enable_pwa;
            $business_card->custom_css = $request->custom_css;
            $business_card->custom_js = $request->custom_js;
            $business_card->save();

            return redirect()->route('user.edit.store.advanced.setting', $request->store_id)->with('success', trans('Store advanced setting updated successfully!'));
        }
    }
}
