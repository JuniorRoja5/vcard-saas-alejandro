@php
    use App\Setting;
    use Illuminate\Support\Facades\DB;

    $config = DB::table('config')->get();
    $settings = Setting::first();

    $recaptcha_configuration = [
        'RECAPTCHA_ENABLE' => env('RECAPTCHA_ENABLE', ''),
        'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SITE_KEY', ''),
        'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY', '')
    ];

    $settings['recaptcha_configuration'] = $recaptcha_configuration;
@endphp

@if ($settings->recaptcha_configuration['RECAPTCHA_ENABLE'] == 'on')
    {{-- Create unique container --}}
    <div class="w-full mb-3" id="{{ $recaptchaId ?? 'recaptcha-default' }}"></div>

    {{-- Load reCAPTCHA script only once (recommended in main layout if reused) --}}
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
        <script>
            window.recaptchaWidgets = {};
            function onloadCallback() {
                document.querySelectorAll('[id^="recaptcha-"]').forEach(function(el) {
                    let id = el.getAttribute('id');
                    window.recaptchaWidgets[id] = grecaptcha.render(id, {
                        'sitekey': '{{ env('RECAPTCHA_SITE_KEY') }}'
                    });
                });
            }
        </script>
@endif
