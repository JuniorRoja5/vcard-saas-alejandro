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

use App\Currency;
use Illuminate\Support\Facades\DB;

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        // Fetch settings from the database
        $config = DB::table('config')->get();
        $currencies = Currency::get();

        // Set decimal value
        $setCurrencyCode = $config[1]->config_value ?? 'USD'; // Default fallback
        $formatType = $config[55]->config_value ?? '1.234.567,89';
        $setDecimalsPlaces = (int)($config[56]->config_value ?? 2);

        // Initialize currency variables
        $currencySymbol = '';
        $symbolFirst = true;

        // Loop through the currencies and find the one matching the setCurrencyCode
        foreach ($currencies as $currency) {
            if ($currency->iso_code === $setCurrencyCode) {
                $currencySymbol = $currency->symbol;
                $symbolFirst = $currency->symbol_first !== "false";
                break;
            }
        }

        // Format the amount based on format type
        $formattedAmount = match ($formatType) {
            '1,234,567.89' => number_format($amount, $setDecimalsPlaces, '.', ','),
            '12,34,567.89' => formatIndianNumber($amount, $setDecimalsPlaces),
            '1.234.567,89' => number_format($amount, $setDecimalsPlaces, ',', '.'),
            '1 234 567,89' => number_format($amount, $setDecimalsPlaces, ',', ' '),
            "1'234'567.89" => number_format($amount, $setDecimalsPlaces, '.', "'"),
            default => number_format($amount, $setDecimalsPlaces, '.', ','),
        };

        return $symbolFirst ? $currencySymbol . $formattedAmount : $formattedAmount . $currencySymbol;
    }
}

if (!function_exists('formatCurrencyCard')) {
    function formatCurrencyCard($amount)
    {
        // Fetch settings from the database
        $config = DB::table('config')->get();
        $formatType = $config[55]->config_value ?? '1.234.567,89';
        $setDecimalsPlaces = (int)($config[56]->config_value ?? 2);

        // Format the amount based on format type
        $formattedAmount = match ($formatType) {
            '1,234,567.89' => number_format($amount, $setDecimalsPlaces, '.', ','),
            '12,34,567.89' => formatIndianNumber($amount, $setDecimalsPlaces),
            '1.234.567,89' => number_format($amount, $setDecimalsPlaces, ',', '.'),
            '1 234 567,89' => number_format($amount, $setDecimalsPlaces, ',', ' '),
            "1'234'567.89" => number_format($amount, $setDecimalsPlaces, '.', "'"),
            default => number_format($amount, $setDecimalsPlaces, '.', ','),
        };

        return $formattedAmount;
    }
}

if (!function_exists('formatIndianNumber')) {
    // Custom function for Indian numbering system
    function formatIndianNumber($amount, $setDecimalsPlaces = 2)
    {
        $amount = number_format($amount, $setDecimalsPlaces, '.', '');

        [$integerPart, $decimalPart] = array_pad(explode('.', $amount), 2, '00');
        $lastThreeDigits = substr($integerPart, -3);
        $otherDigits = substr($integerPart, 0, -3);

        if ($otherDigits !== '') {
            $otherDigits = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $otherDigits);
            $formattedInteger = $otherDigits . ',' . $lastThreeDigits;
        } else {
            $formattedInteger = $lastThreeDigits;
        }

        return $formattedInteger . '.' . $decimalPart;
    }
}
