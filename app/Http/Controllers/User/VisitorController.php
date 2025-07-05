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

namespace App\Http\Controllers\User;

use App\Setting;
use App\Visitor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VisitorController extends Controller
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

    // Visitors
    public function index(Request $request, $id)
    {
        // Queries
        $settings = Setting::where('status', 1)->first();

        // Enquiries
        $visitors = Visitor::where('card_id', $id)->orderBy('id', 'desc')->get();

        return view('user.pages.cards.visitors', compact('visitors', 'settings'));
    }
}
