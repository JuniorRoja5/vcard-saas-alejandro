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

use App\User;
use App\Setting;
use App\Currency;
use Carbon\Carbon;
use App\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
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

    // Plans
    public function index()
    {
        // Queries
        $plans = DB::table('plans')->where('is_private', 0)->where('status', 1)->get();
        $config = DB::table('config')->get();
        $free_plan = Transaction::where('user_id', Auth::user()->id)->where('transaction_amount', '0')->count();
        $plan = User::where('user_id', Auth::user()->user_id)->first();
        $active_plan = json_decode($plan->plan_details);
        $settings = Setting::where('status', 1)->first();
        $currency = Currency::where('iso_code', $config[1]->config_value)->first();
        $remaining_days = 0;

        // Check active plan in user
        if (isset($active_plan)) {
            // Create Carbon instance from the stored plan validity datetime
            $plan_validity = Carbon::createFromFormat('Y-m-d H:i:s', Auth::user()->plan_validity);

            // Extract original time components
            $hour = (int) $plan_validity->format('H');
            $minute = (int) $plan_validity->format('i');
            $second = (int) $plan_validity->format('s');

            // Set the same time explicitly (optional — only needed if you want to make sure it's correct)
            $plan_validity->setTime($hour, $minute, $second);

            $current_date = Carbon::now();

            // Calculate exact remaining days
            $remaining_days = $current_date->diffInDays($plan_validity, false);

            // Convert to integer
            $remaining_days = $remaining_days;
        }

        return view('user.pages.plans.plans', compact('plans', 'settings', 'currency', 'active_plan', 'remaining_days', 'config', 'free_plan'));
    }
}
