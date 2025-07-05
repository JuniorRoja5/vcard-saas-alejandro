@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

@section('content')
<div class="page-wrapper">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h2 class="page-title">
                        {{ __('Checkout') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-fluid mt-3">

            {{-- Failed Alert --}}
            @if(Session::has('failed'))
                <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
                    <div class="d-flex">
                        <div>
                            {{ Session::get('failed') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Success Alert --}}
            @if(Session::has('success'))
                <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">
                    <div class="d-flex">
                        <div>
                            {{ Session::get('success') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">
                            {{ __('Choosed Plan') }} : {{ __($plan_details->plan_name) }}
                        </h3>
                        <button type="button" class="btn btn-primary" id="customCheckout">{{ __('Buy Now!') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('user.includes.footer')
</div>

{{-- Custom JS --}}
@push('custom-js')
<button type="button" class="btn btn-primary" id="customCheckout">{{ __('Buy Now!') }}</button>
<script src="https://cdn.paddle.com/paddle/paddle.js"></script>
    <script>
        document.getElementById('customCheckout').addEventListener('click', function () {
            Paddle.Checkout.open({
                override: "{{ $data['response']['url'] }}"
            });
        });
    </script>
@endpush
@endsection