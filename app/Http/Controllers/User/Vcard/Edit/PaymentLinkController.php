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

use App\Payment;
use App\Setting;
use App\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentLinkController extends Controller
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

    // Payment links
    public function paymentLinks(Request $request, $id)
    {
        // Queries
        $business_card = BusinessCard::where('card_id', $id)->first();

        // Check business card
        if ($business_card == null) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        } else {
            // Queries
            $payments = Payment::where('card_id', $id)->orderBy('position', 'asc')->get();
            $plan = DB::table('users')->where('user_id', Auth::user()->user_id)->where('status', 1)->first();
            $plan_details = json_decode($plan->plan_details);
            $settings = Setting::where('status', 1)->first();

            if ($plan_details->no_of_payments > 0) {
                return view('user.pages.edit-cards.edit-payment-links', compact('payments', 'plan_details', 'settings'));
            } else if ($plan_details->no_of_services > 0) {
                return redirect()->route('user.edit.services', request()->segment(3));
            } else if ($plan_details->no_of_vcard_products > 0) {
                return redirect()->route('user.edit.vproducts', request()->segment(3));
            } else if ($plan_details->no_of_galleries > 0) {
                return redirect()->route('user.edit.galleries', request()->segment(3));
            } else if ($plan_details->no_testimonials > 0) {
                return redirect()->route('user.edit.testimonials', request()->segment(3));
            } else {
                return redirect()->route('user.edit.popups', request()->segment(3));
            }
        }
    }

    // Update payment links
    public function updatePaymentLinks(Request $request, $id)
    {
        // Find business card
        $business_card = BusinessCard::where('card_id', $id)->first();
        if (!$business_card) {
            return redirect()->route('user.cards')->with('failed', trans('Card not found!'));
        }

        // Check if new icons are submitted
        if ($request->has('icon') && is_array($request->icon)) {

            // Fetch user plan
            $plan = DB::table('users')->where('user_id', Auth::id())->where('status', 1)->first();
            $plan_details = json_decode($plan->plan_details ?? '{}');

            // Validate limit
            if (count($request->icon) <= ($plan_details->no_of_payments ?? 0)) {
                return redirect()->route('user.edit.payment.links', $id)->with('failed', trans('Maximum links limit exceeded.'));
            }

            // Delete old payment links
            Payment::where('card_id', $id)->delete();

            // Loop through and save each payment link
            foreach ($request->icon as $i => $icon) {
                if (
                    isset($request->type[$i]) &&
                    isset($request->label[$i]) &&
                    isset($request->value[$i])
                ) {
                    $payment = new Payment();
                    $payment->card_id = $id;
                    $payment->type = $request->type[$i];
                    $payment->icon = $icon;
                    $payment->label = $request->label[$i];
                    $payment->position = $i + 1;

                    // Handle file upload if type is image
                    if ($request->type[$i] === 'image' && $request->hasFile("value.$i")) {
                        $file = $request->file("value.$i");
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('payments', $filename, 'public');
                        $payment->content = "storage/" . $path;
                    } else {
                        $payment->content = $request->value[$i];
                    }

                    $payment->save();
                } else {
                    // Incomplete data
                    Payment::where('card_id', $id)->delete();
                    return redirect()->route('user.edit.payment.links', $id)->with('failed', trans('Please fill out all required fields.'));
                }
            }

            return redirect()->route('user.edit.services', $id)->with('success', trans('Payment links are updated.'));
        }

        return redirect()->route('user.edit.services', $id)->with('success', trans('Payment links are updated.'));
    }
}
