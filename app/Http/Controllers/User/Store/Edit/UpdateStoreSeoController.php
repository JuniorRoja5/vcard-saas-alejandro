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

use App\Setting;
use App\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UpdateStoreSeoController extends Controller
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

    // Edit store seo
    public function editSeo(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();
        $config   = DB::table('config')->get();
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.edit-store.seo', compact('business_card', 'settings', 'config'));
    }

    // Update store seo
    public function updateSeo(Request $request)
    {
        // Validate
        $this->validate($request, [
            'store_id' => 'required',
            'meta_title' => 'required',
            'meta_description' => 'required',
            'meta_keywords' => 'required',
        ]);

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

        // Update store seo
        $business_card = BusinessCard::where('card_id', $request->store_id)->first();

        // Check favicon
        if ($request->hasFile('favicon')) {
            // Save favicon image
            $favicon = $request->file('favicon');

            // Unique file name
            $fileName = uniqid() . '.png';

            // Store the file in storage/app/public/vcard/favicons
            Storage::disk('public')->putFileAs('store/favicons', $favicon, $fileName);

            // Access the file via public URL
            $favicon = 'storage/store/favicons/' . $fileName;
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
        
        $business_card->save(['seo_configurations']);

        return redirect()->route('user.edit.store.seo', $request->store_id)->with('success', __('Updated!'));
    }
}
