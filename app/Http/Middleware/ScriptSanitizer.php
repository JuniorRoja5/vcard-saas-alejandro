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

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;
use Symfony\Component\HttpFoundation\Response;

class ScriptSanitizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            if (isset($value) && is_string($value)) {
                // Skip cleaning for trusted fields
                if (in_array($key, ['custom_js', 'custom_scripts'])) {
                    return;
                }
                
                // Step 1: Decode HTML entities like &amp; → &
                $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                // Step 2: Sanitize using HTMLPurifier
                $cleaned = Purifier::clean($decoded);

                // Step 3: Decode again to reverse Purifier's re-encoding
                $value = html_entity_decode($cleaned, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        });

        // Merge the cleaned input back into the request
        $request->merge($input);

        return $next($request);
    }
}
