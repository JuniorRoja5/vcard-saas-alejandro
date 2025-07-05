<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $card_details->title }}</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Store icon and color --}}
    <link rel="icon" href="{{ url($business_card_details->profile) }}" sizes="512x512" type="image/png" />
    <link rel="apple-touch-icon" href="{{ url($business_card_details->profile) }}">

    <meta name="theme-color" content="green" />

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="application-name" content="{{ $card_details->title }}">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-title" content="{{ $card_details->title }}">

    <!-- Tile for Win8 -->
    <meta name="msapplication-TileColor" content="green">
    <meta name="msapplication-TileImage" content="{{ url($business_card_details->profile) }}">

    {{-- CSS --}}
    <link href="{{ url('css/tabler.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="{{ url('app/css/store.css') }}" rel="stylesheet">

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.4px;
        }
    </style>

    {{-- Check business details --}}
    @if ($business_card_details != null)
        @php
            $custom_css = $business_card_details->custom_css;
            $custom_js = $business_card_details->custom_js;

            // Ensure <style> tags for custom CSS
            if (strpos($custom_css, '<style>') === false && strpos($custom_css, '</style>') === false) {
                $custom_css = "<style>" . $custom_css . "</style>";
            }

            // Ensure <script> tags for custom JS
            if (strpos($custom_js, '<script>') === false && strpos($custom_js, '</script>') === false) {
                $custom_js = "<script>" . $custom_js . "</script>";
            }
        @endphp

        {!! $custom_css !!}
        {!! $custom_js !!}
    @endif

    {{-- JS --}}
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/main.js') }}"></script>
    <script src="{{ url('js/sweetalert.all.js') }}"></script>

    {{-- SEO Tags --}}
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
</head>

<body dir="{{ App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') ? 'rtl' : 'ltr' }}">
    <!-- Preloader -->
    <div class="page page-center preloader-wrapper">
        <div class="container container-slim py-4">
            <div class="text-center">
                <div class="spinner-border text-green" role="status"></div>
            </div>
        </div>
    </div>

    {{-- Page --}}
    <div id="wrapper" class="page">
        <!-- Navbar -->
        @include('templates.includes.header', ['bg_color' => 'green', 'badge_color' => 'danger'])

        <div class="page-wrapper mt-4 p-3">
            <div class="page-body">
                <div class="container-xl">
                    <!-- Categories -->
                    <div class="col-md-12 my-3">

                        <h2 class="pt-4 pb-5 text-start position-relative fs-custom fw-bold">
                            {{ __('Categories') }}
                            <div class="position-absolute start-0 bg-green bottom-bar2"></div>
                        </h2>

                        <!-- All Categories -->
                        @if (count($categories) > 0)
                            <div class="col-12 col-lg-3 my-3">
                                <div class="row">
                                    <!-- Foreach -->
                                    @foreach ($categories as $category)
                                        <div class="cursor-pointer mb-3 col-6 col-md-3">
                                            <a
                                                href="{{ url($business_card_details->card_url) . '?category=' . strtolower($category->category_name) }}">
                                                <div class="card radius-img">
                                                    <div
                                                        class="card-body d-flex flex-column align-items-center justify-content-center gap-2 p-3">
                                                        <!-- Thumbnail -->
                                                        <div class="ratio ratio-1x1">
                                                            <img src="{{ url($category->thumbnail) }}"
                                                                class="object-cover radius-img w-100"
                                                                alt="{{ $category->category_name }}" />
                                                        </div>
                                                        <!-- Category Name -->
                                                        <div class="">
                                                            <h3 class="responsive-title mt-2 mb-0">
                                                                {{ __($category->category_name) }}
                                                            </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="col-12 my-3">
                                <h2 class="text-center">{{ __('No Categories Found') }}</h2>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer footer-transparent d-print-none mb-7 mb-lg-0">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <!-- Social Links -->
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><a href="{{ $shareComponent['facebook'] }}"
                                        target="_blank" class="link-light"><i
                                            class="ti ti-brand-facebook-filled text-green"></i></a></li>
                                <li class="list-inline-item"><a href="{{ $shareComponent['twitter'] }}"
                                        class="link-light"><i class="ti ti-brand-twitter-filled text-green"></i></a>
                                </li>
                                <li class="list-inline-item"><a href="{{ $shareComponent['linkedin'] }}"
                                        target="_blank" class="link-light"><i
                                            class="ti ti-brand-linkedin text-green"></i></a></li>
                                <li class="list-inline-item"><a href="{{ $shareComponent['telegram'] }}"
                                        target="_blank" class="link-light"><i
                                            class="ti ti-brand-telegram text-green"></i></a></li>
                                <li class="list-inline-item"><a href="{{ $shareComponent['whatsapp'] }}"
                                        target="_blank" class="link-light"><i
                                            class="ti ti-brand-whatsapp text-green"></i></a></li>
                            </ul>
                        </div>
                        <!-- Copyright -->
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                @if ($plan_details['hide_branding'] == 1)
                                    <li class="list-inline-item">
                                        {{ __('Copyright') }} &copy; <span id="year"></span> <a
                                            href="{{ url($business_card_details->card_url) }}"
                                            class="link-light text-green"><strong>{{ $card_details->title }}</strong></a>.
                                        {{ __('All rights reserved.') }}
                                    </li>
                                @else
                                    <li class="list-inline-item">
                                        {{ __('Copyright') }} &copy; <span id="year"></span> <a
                                            href="{{ url($business_card_details->card_url) }}"
                                            class="link-light text-green"><strong>{{ config('app.name') }}</strong></a>.
                                        {{ __('All rights reserved.') }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Bottom Navbar -->
        @include('templates.includes.bottom-bar', ['color' => 'green', 'bg' => 'light'])
        <!-- End Bottom Navbar -->
    </div>

    {{-- Cart Items --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title fs-custom" id="offcanvasEndLabel">{{ __('Cart Items') }}</h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <!-- Cart Items -->
                <div class="row" id="cart_items"></div>
            </div>

            <div id="empty-cart" class="p-3">
                <!-- Empty Cart -->
                <p class="px-4 py-4 mb-4 text-center fs-2">{{ __('Your cart is empty.') }}</p>

                <!-- Start Shopping -->
                <a class="btn btn-green d-flex btn-effect" data-bs-dismiss="offcanvas"
                    aria-label="Close">{{ __('Start Shopping') }}</a>
            </div>
        </div>
        <div class="offcanvas-footer">
            <div id="cart-pricing"></div>
            <!-- Place Order -->
            <a class="btn btn-green fs-2 btn-effect" id="place-order"
                onclick="placeOrder()">{{ __('Place WhatsApp Order') }}</a>
        </div>
    </div>

    {{-- Place order --}}
    <div class="modal modal-blur fade" id="orderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Please fill following details:') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label required" for="cus_name">{{ __('Full Name') }}</label>
                        <input type="text" class="form-control" id="cus_name" required />
                    </div>
                    <!-- Mobile -->
                    <div class="mb-3">
                        <label class="form-label required" for="cus_mobile">{{ __('Mobile') }}</label>
                        <input type="number" class="form-control" id="cus_mobile" required />
                    </div>
                    <!-- Address -->
                    <div class="mb-3">
                        <label class="form-label required" for="cus_address">{{ __('Address') }}</label>
                        <input type="text" class="form-control" id="cus_address" required />
                    </div>
                    
                    {{-- Check delivery options --}}
                    @if ($deliveryOptions != null)
                        <!-- Delivery Type -->
                        <div class="mb-3">
                            <div class="form-label required mb-3">{{ __('Delivery Type') }}</div>
                            <div class="d-flex flex-column gap-2">
                                {{-- Order For Delivery --}}
                                @if (isset($deliveryOptions->order_for_delivery) && $deliveryOptions->order_for_delivery == 1)
                                    <label class="form-check">
                                        <input class="form-check-input" type="radio" name="cus_delivery_type"
                                            id="delivery_order" value="Order For Delivery" checked>
                                        <span class="form-check-label">{{ __('Order For Delivery') }}</span>
                                    </label>
                                @endif

                                {{-- Take Away --}}
                                @if (isset($deliveryOptions->take_away) && $deliveryOptions->take_away == 1)
                                    <label class="form-check">
                                        <input class="form-check-input" type="radio" name="cus_delivery_type"
                                            id="take_away" value="Take Away">
                                        <span class="form-check-label">{{ __('Take Away') }}</span>
                                    </label>
                                @endif

                                {{-- Dine In --}}
                                @if (isset($deliveryOptions->dine_in) && $deliveryOptions->dine_in == 1)
                                <label class="form-check">
                                    <input class="form-check-input" type="radio" name="cus_delivery_type"
                                        id="dine_in" value="Dine In">
                                    <span class="form-check-label">{{ __('Dine In') }}</span>
                                </label>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    {{-- Notes --}}
                    <div class="mb-3">
                        <label class="form-label" for="cus_notes">{{ __('Notes') }}</label>
                        <textarea class="form-control" id="cus_notes" name="cus_notes" rows="3"></textarea>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <!-- Close -->
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <!-- Confirm -->
                    <button type="button" class="btn btn-green"
                        onclick="confirmOrder()">{{ __('Confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error alert -->
    <div class="alert alert-important alert-danger alert-float" id="errorAlertContainer" role="alert">
        <div id="errorAlertMessage"></div>
    </div>

    <!-- Success alert -->
    <div class="alert alert-important alert-success alert-float" id="successAlertContainer" role="alert">
        <div id="successAlertMessage"></div>
    </div>

    <!-- Core -->
    <script type="text/javascript" src="{{ url('js/tabler.min.js') }}"></script>
    <script src="{{ url('js/script.js') }}"></script>
    <script src="{{ url('js/data-filter.js') }}"></script>

    {{-- Custom JS --}}
    @yield('custom-js')

    <script>
        // Injected from Laravel
        const config = {
            currencyCode: @json($currency),
            formatType: @json($config[55]->config_value ?? '1.234.567,89'),
            decimalPlaces: @json((int) ($config[56]->config_value ?? 2))
        };

        // Currencies
        const currencies = @json(App\Currency::select('iso_code', 'symbol', 'symbol_first')->get());

        // Determine currency symbol and position
        let currencySymbol = '';
        let symbolFirst = true;

        // Loop through the currencies and find the one matching the setCurrencyCode
        for (let i = 0; i < currencies.length; i++) {
            if (currencies[i].iso_code === config.currencyCode) {
                currencySymbol = currencies[i].symbol;
                symbolFirst = currencies[i].symbol_first !== false && currencies[i].symbol_first !== "false";
                break;
            }
        }

        // Format the amount based on format type
        function jsFormatCurrency(amount, decimalPlaces = 2, formatType = "1,234,567.89", currencySymbol =
            "{{ $currency }}") {
            let formattedAmount;

            switch (formatType) {
                case "1,234,567.89": // US style
                    formattedAmount = amount.toLocaleString('en-US', {
                        minimumFractionDigits: decimalPlaces,
                        maximumFractionDigits: decimalPlaces
                    });
                    break;

                case "1.234.567,89": // German style
                    formattedAmount = amount.toLocaleString('de-DE', {
                        minimumFractionDigits: decimalPlaces,
                        maximumFractionDigits: decimalPlaces
                    });
                    break;

                case "1 234 567,89": // French style
                    formattedAmount = amount.toLocaleString('fr-FR', {
                        minimumFractionDigits: decimalPlaces,
                        maximumFractionDigits: decimalPlaces
                    });
                    break;

                case "1'234'567.89": // Swiss style
                    formattedAmount = amount.toFixed(decimalPlaces).replace(/\B(?=(\d{3})+(?!\d))/g, "'");
                    break;

                case "12,34,567.89": // Indian style
                    formattedAmount = formatIndianCurrency(amount, decimalPlaces);
                    break;

                default:
                    formattedAmount = amount.toLocaleString('en-US', {
                        minimumFractionDigits: decimalPlaces,
                        maximumFractionDigits: decimalPlaces
                    });
            }

            return symbolFirst ? currencySymbol + formattedAmount : formattedAmount + currencySymbol;
        }

        // Custom function for Indian numbering system
        function formatIndianCurrency(amount, decimalPlaces = 2) {
            let [integerPart, decimalPart] = amount.toFixed(decimalPlaces).split(".");

            let lastThree = integerPart.slice(-3);
            let otherNumbers = integerPart.slice(0, -3);
            if (otherNumbers !== '') {
                otherNumbers = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",");
                integerPart = otherNumbers + "," + lastThree;
            } else {
                integerPart = lastThree;
            }

            return integerPart + "." + decimalPart;
        }
    </script>

    <script>
        // Global variables
        var cart = [];
        var whatsAppNumber = "{{ $enquiry_button }}";
        var whatsAppMessage = `{!! $whatsapp_msg !!}`;
        var currency = "";

        // Function to initialize page
        function initializePage() {
            $('.preloader-wrapper').fadeOut('slow');
            getData();
        }

        // Fetch data function
        function getData() {
            var storageKey = "cart_" + "{{ $business_card_details->card_id }}";
            cart = localStorage.getItem(storageKey) ? JSON.parse(localStorage.getItem(storageKey)) : [];
            updateList();
            updateBadge();
        }

        // Update product image
        function updateSrc(element) {
            var productImage = $(element).attr("src");
            var productName = $(element).attr("alt");
            $("#product_image").attr("src", productImage);
            $("#product_name").text(productName);
        }

        // Update cart list
        function updateList() {
            var cart_items = "";
            var grandTotal = 0;

            cart.forEach((item, index) => {
                const total_price = item.qty * Number(item.price);
                grandTotal += total_price;

                // PHP values
                var formatType = "{{ $config[55]->config_value ?? '1.234.567,89' }}";
                var setDecimalsPlaces = {{ $config[56]->config_value ?? 2 }};

                cart_items += `<div class="col-12 mb-3">`;
                cart_items += `<div class="card p-3">`;
                cart_items += `<div class="d-flex align-items-center">`;

                // LEFT: Image
                cart_items += `
                <div class="me-3 flex-shrink-0" style="width: 100px; height: 100px;">
                    <img src="${item.product_image}" class="img-fluid rounded object-fit-cover h-100 w-100" alt="${item.product_name}" />
                </div>`;

                // RIGHT: Product Info
                cart_items += `<div class="flex-grow-1">`;
                cart_items += `<h4 class="text-dark fs-2 mb-1">${item.product_name}</h4>`;
                cart_items += `<small class="text-muted d-block">${item.subtitle}</small>`;
                cart_items +=
                    `<p class="text-secondary mt-1 mb-2 fw-semibold fs-3">${jsFormatCurrency(total_price, setDecimalsPlaces, formatType)}</p>`;

                // Buttons: Quantity & Actions
                cart_items += `<div class="d-flex align-items-center flex-wrap">`;

                // Round buttons
                cart_items +=
                    `<button onclick="reduceQty(${index})" class="btn text-dark d-flex align-items-center justify-content-center" style="width:35px;height:35px;"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="m-0 icon icon-tabler icons-tabler-outline icon-tabler-minus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /></svg></button>`;
                cart_items +=
                    `<span class="text-dark p-0 d-flex align-items-center justify-content-center fs-3 fw-bold" style="width:35px;height:35px;">${item.qty}</span>`;
                cart_items +=
                    `<button onclick="addQty(${index})" class="btn text-dark d-flex align-items-center justify-content-center" style="width:35px;height:35px;"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg></button>`;
                cart_items +=
                    `<button onclick="removeFromCart(${index})" class="ms-3 btn btn-danger p-0 d-flex align-items-center justify-content-center" style="width:35px;height:35px;"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></button>`;

                cart_items += `</div>`; // end buttons
                cart_items += `</div>`; // end text/info
                cart_items += `</div>`; // end row
                cart_items += `</div>`; // end card
                cart_items += `</div>`; // end col

            });

            if (grandTotal > 0) {
                // PHP values
                var formatType = "{{ $config[55]->config_value ?? '1.234.567,89' }}";
                var setDecimalsPlaces = {{ $config[56]->config_value ?? 2 }};

                $("#cart-pricing").html(
                    `<h3 class="font-bold fs-2 d-flex justify-content-between"><span>{{ __('Grand total') }}</span><span class="text-green">${jsFormatCurrency(grandTotal, setDecimalsPlaces, formatType)}</span></h3>`
                );
            } else {
                $("#cart-pricing").html(``);
            }

            $("#cart_items").html(cart_items);
        }

        // Update badge function
        function updateBadge() {
            var badgeCount = cart.length;
            if (badgeCount > 0) {
                $("#empty-cart").hide();
                $("#badge").text(badgeCount).show();
                $("#place-order").show().attr("class", "btn btn-green d-flex");
            } else {
                $("#place-order").hide().attr("class", "btn btn-green d-none");
                $("#badge").hide();
                $("#empty-cart").show();
            }
        }

        // Reduce quantity function
        function reduceQty(i) {
            if (cart[i].qty == 1) {
                removeFromCart(i);
            } else {
                cart[i].qty--;
                updateBadge();
                updateList();
            }
            updateStorage();
        }

        // Add quantity function
        function addQty(i) {
            cart[i].qty++;
            updateBadge();
            updateList();
            updateStorage();
        }

        // Remove from cart function
        function removeFromCart(i) {
            cart.splice(i, 1);
            successAlert(`{{ __('Item Removed') }}`);
            updateStorage();
            updateBadge();
            updateList();
        }

        // Business Hours
        const businessHours = @json(json_decode($businessHours->business_hours ?? '{}', true)); // convert PHP JSON string to JS object

        // Check if within business hours
        function isWithinBusinessHours() {
            "use strict";

            const now = new Date();

            const days = [
                'sunday', 'monday', 'tuesday', 'wednesday',
                'thursday', 'friday', 'saturday'
            ];
            const day = days[now.getDay()];

            const currentTime = now.getHours().toString().padStart(2, '0') + ':' +
                                now.getMinutes().toString().padStart(2, '0');

            const hours = businessHours[day];

            if (!hours || !hours.start || !hours.end) return false;

            return (currentTime >= hours.start && currentTime <= hours.end);
        }

        // Place order
        function placeOrder() {
            "use strict";

            if (isWithinBusinessHours()) {
                const myModal = new bootstrap.Modal(document.getElementById('orderModal'), {
                    keyboard: false
                });
                myModal.show();
            } else {
                // Show error message
                errorAlert('{{ __('Sorry, we are currently closed.') }}');
            }
        }

        // Function to confirm order details
        function confirmOrder() {
            var cusName = document.getElementById('cus_name').value;
            var cusMobile = document.getElementById('cus_mobile').value;
            var cusAddress = document.getElementById('cus_address').value;
            var cusDeliveryType = document.querySelector('input[name="cus_delivery_type"]:checked').value;
            var cusNotes = document.getElementById('cus_notes').value;

            if (!cusName || !cusMobile || !cusAddress || !cusDeliveryType) {
                errorAlert('{{ __('Please fill out all fields.') }}');
                return false;
            }

            createWhatsAppLink([cusName, cusMobile, cusAddress, cusDeliveryType, cusNotes]);
            var myModalEl = document.getElementById('orderModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
        }

        // Function to create WhatsApp link for order details
        function createWhatsAppLink(cusDetails) {
            "use strict";
            // Check if customer details are valid
            if (cusDetails[0].length >= 3 && cusDetails[1].length >= 4) {
                // Initialize products list and grand total
                let productsList = `\n- - - - - - - - - - - - - - - - - - - -\n📦 *{{ __('Order Details:') }}* \n\n`;
                let grandTotal = 0;

                // PHP values
                var formatType = "{{ $config[55]->config_value ?? '1.234.567,89' }}";
                var setDecimalsPlaces = {{ $config[56]->config_value ?? 2 }};

                // Iterate through cart items
                cart.forEach(item => {
                    const itemCost = Number(item.qty) * Number(item.price);
                    const cartPrice = Number(item.price);

                    // Append product details to products list
                    productsList +=
                        `${item.product_name} - ${item.qty} X  ${jsFormatCurrency(cartPrice, setDecimalsPlaces, formatType)} = ${jsFormatCurrency(itemCost, setDecimalsPlaces, formatType)}\n`;
                    grandTotal += itemCost;
                });

                // Place order ajax
                $.ajax({
                    url: "{{ route('store.order.place') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        store_id: "{{ $business_card_details->card_id }}",
                        customer_name: cusDetails[0],
                        customer_phone: cusDetails[1],
                        delivery_address: cusDetails[2],
                        delivery_note: cusDetails[4],
                        delivery_method: cusDetails[3],
                        order_items: cart,
                        total_price: grandTotal,
                    },
                    success: function (data) {
                        // Check if order was placed successfully
                        if (data.status == "success") {
                            // Add total and customer details to products list
                            productsList += `\n- - - - - - - - - - - - - - - - - - - -\n`;
                            productsList +=
                                `*{{ __('Total') }}* : *${jsFormatCurrency(grandTotal, setDecimalsPlaces, formatType)}*\n\n`;
                            productsList += `📞 *{{ __('Customer Details:') }}* \n`;
                            productsList += `{{ __('Customer Name') }} : ${cusDetails[0]}\n`;
                            productsList += `{{ __('Contact Number') }} : ${cusDetails[1]}\n`;
                            productsList += `{{ __('Delivery Address') }} : ${cusDetails[2]}\n`;
                            productsList += `{{ __('Delivery Type') }} : ${cusDetails[3]}\n`;

                            if (cusDetails[4]) {
                                productsList += `{{ __('Notes') }} : ${cusDetails[4]}\n\n`;
                            } else {
                                productsList += `\n\n`;
                            }

                            // Prepare WhatsApp share content
                            let waShareContent = `🎉 *{{ __('New Order') }}* \n`;
                            waShareContent += productsList + `*${whatsAppMessage}*`;

                            // Construct WhatsApp link and open in new tab
                            const link = `https://api.whatsapp.com/send/?phone=${whatsAppNumber}&text=${encodeURI(waShareContent)}`;
                            window.open(link, '_blank');

                            // Reset cart and update local storage
                            cart = [];
                            updateStorage();

                            // Show success alert
                            successAlert('{{ __('Order Placed!') }}');
                        } else {
                            // Show error message
                            errorAlert(data.message);
                        }
                    },
                    error: function (error) {
                        // Show error message
                        errorAlert(error.responseJSON.message);
                    }
                });

                errorAlert('{{ __('Order Failed!') }}');
            } else {
                // If customer details are invalid, prompt to place order
                placeOrder();
            }
        }

        // Update local storage function
        function updateStorage() {
            localStorage.setItem("cart_" + "{{ $business_card_details->card_id }}", JSON.stringify(cart));
        }

        // Show alert function
        function showAlert(containerId, message) {
            const alertContainer = document.getElementById(containerId);
            const alertMessage = alertContainer.querySelector('div');
            alertMessage.innerHTML = message;
            alertContainer.classList.add('show');
            alertContainer.style.display = 'block';

            // Optional styling (add only once)
            alertContainer.style.maxWidth = '500px';
            alertContainer.style.margin = '0 auto';
            alertContainer.style.width = '75%'; // Optional for mobile responsiveness
            alertContainer.style.textAlign = 'center';

            setTimeout(() => {
                alertContainer.classList.remove('show');
                setTimeout(() => {
                    alertContainer.style.display = 'none';
                }, 1000);
            }, 3000);
        }

        // Error alert function
        function errorAlert(message) {
            showAlert('errorAlertContainer', message);
        }

        // Success alert function
        function successAlert(message) {
            showAlert('successAlertContainer', message);
        }

        // Initial function call
        initializePage();
    </script>
</body>

</html>
