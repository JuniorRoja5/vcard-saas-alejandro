<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

trait BuildsSettings
{
    protected function buildSettings(): \stdClass
    {
        $kv = [];
        try {
            foreach (DB::table('config')->select(['config_key','config_value'])->get() as $row) {
                $kv[$row->config_key] = $row->config_value;
            }
        } catch (\Throwable $e) {}

        $s = new \stdClass();
        $s->site_name = $kv['site_name'] ?? config('app.name', 'VCard');
        $s->favicon = $kv['favicon'] ?? 'images/favicon.png';
        $s->site_logo = $kv['site_logo'] ?? ($kv['logo'] ?? 'images/logo.png');
        $s->logo_light = $kv['logo_light'] ?? $s->site_logo;
        $s->logo_dark = $kv['logo_dark'] ?? $s->site_logo;
        $s->site_description = $kv['site_description'] ?? '';
        $s->site_keywords = $kv['site_keywords'] ?? '';
        $s->primary_color = $kv['primary_color'] ?? '#0d6efd';
        $s->secondary_color = $kv['secondary_color'] ?? '#6c757d';
        $s->footer_text = $kv['footer_text'] ?? '';
        $s->facebook = $kv['facebook'] ?? null;
        $s->twitter = $kv['twitter'] ?? null;
        $s->instagram = $kv['instagram'] ?? null;
        $s->youtube = $kv['youtube'] ?? null;
        $s->google_analytics_id = $kv['google_analytics_id'] ?? ($kv['ga_measurement_id'] ?? null);
        $s->google_adsense_code = $kv['google_adsense_code'] ?? 'DISABLE_ADSENSE_ONLY';
        $s->google_tag_manager_id = $kv['google_tag_manager_id'] ?? null;
        $s->facebook_pixel_id = $kv['facebook_pixel_id'] ?? null;
        $s->custom_css = $kv['custom_css'] ?? '';
        $s->custom_scripts = $kv['custom_scripts'] ?? ($kv['custom_js'] ?? '');
        $s->recaptcha_site_key = $kv['recaptcha_site_key'] ?? null;
        $s->recaptcha_secret_key = $kv['recaptcha_secret_key'] ?? null;
        return $s;
    }
}