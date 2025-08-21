@php
    if (!isset($settings)) {
        $settings = (object)[
            'site_name'=>config('app.name', 'VCard'),
            'favicon'=>'images/favicon.png',
        ];
    }
@endphp