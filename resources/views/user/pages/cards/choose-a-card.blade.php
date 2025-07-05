@extends('user.layouts.index', ['header' => true, 'nav' => true, 'demo' => true, 'settings' => $settings])

{{-- Custom CSS --}}
@section('css')
<style>
.card-link-option {
    transition: all 0.3s ease;
    border: 2px solid transparent;
    cursor: pointer;
}

.card-link-option:hover {
    border-color: #0066cc !important;
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1);
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1);
}

.icon-circle {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin: 0 auto;
}

.preview-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin: 1rem 0;
}

.preview-avatar {
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    border-radius: 4px;
    margin-right: 0.5rem;
}

.feature-check {
    color: #198754;
    margin-right: 0.5rem;
}
</style>
@endsection

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
                        {{ __('Choose a Card Type') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">
            {{-- Failed --}}
            @if(Session::has("failed"))
            <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('failed')}}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            {{-- Success --}}
            @if(Session::has("success"))
            <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('success')}}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif
            
            <div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="text-center mb-5">
            <h1 class="h2 mb-3">Choose what you want to create</h1>
            <p class="text-muted fs-5">Pick the format that best fits what you're sharing</p>
        </div>
        
        <div class="row g-4">
            <!-- Links Card (Personal) -->
            <div class="col-md-6">
                <div class="card card-link-option h-100" data-type="personal" onclick="chooseCardTpe({value: 'personal'})">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="icon-circle bg-primary-subtle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                                </svg>
                            </div>
                            <h3 class="mt-3 mb-2">🔗 CREATE LINKS</h3>
                            <p class="text-muted">Perfect for personal pages and social sharing</p>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="mb-3">Perfect for:</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <span class="feature-check">✓</span>Personal pages
                                </li>
                                <li class="mb-2">
                                    <span class="feature-check">✓</span>Social media links
                                </li>
                                <li class="mb-2">
                                    <span class="feature-check">✓</span>Bio pages
                                </li>
                                <li class="mb-2">
                                    <span class="feature-check">✓</span>Influencers & creators
                                </li>
                                <li class="mb-2">
                                    <span class="feature-check">✓</span>Link in bio replacement
                                </li>
                            </ul>
                        </div>
                        
                        <div class="preview-box mb-4">
                            <small class="text-muted d-block mb-2">Preview example:</small>
                            <div class="d-flex align-items-center">
                                <div class="preview-avatar bg-primary text-white">JD</div>
                                <div>
                                    <div class="fw-bold small">John Doe</div>
                                    <div class="text-muted small">@johndoe</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="button" class="btn btn-outline-primary btn-lg w-100">
                                Create Links Page →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Products Card (Business) -->
            <div class="col-md-6">
                <div class="card card-link-option h-100" data-type="business" onclick="chooseCardTpe({value: 'business'})">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="icon-circle bg-success-subtle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success">
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                            </div>
                            <h3 class="mt-3 mb-2">💼 CREATE PRODUCTS</h3>
                            <p class="text-muted">Perfect for selling and business pages</p>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="mb-3">Perfect for:</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <span class="feature-check">✓</span>Selling digital products
                                </li>
                                <li class="mb-2">
                                    <span class="feature-check">✓</span>Services & consultations
                                </li>
                                <li class="mb-2">
                                    <span class="feature-check">✓</span>Business pages
                                </li>
                                <li class="mb-2">
                                    <span class="feature-check">✓</span>Online stores
                                </li>
                                <li class="mb-2">
                                    <span class="feature-check">✓</span>Course creators
                                </li>
                            </ul>
                        </div>
                        
                        <div class="preview-box mb-4">
                            <small class="text-muted d-block mb-2">Preview example:</small>
                            <div class="d-flex align-items-center">
                                <div class="preview-avatar bg-success text-white">BC</div>
                                <div>
                                    <div class="fw-bold small">Business Co.</div>
                                    <div class="text-muted small">Digital Products</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="button" class="btn btn-outline-success btn-lg w-100">
                                Create Business Page →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @include('user.includes.footer')
</div>

{{-- Custom JS --}}
@push('custom-js')
<script>
    function chooseCardTpe(selectedCard) {
    var selectedCardValue = selectedCard.value;
    if (selectedCardValue == "business") {
        window.location = `{{ route('user.create.unified', 'type=business') }}`;
    } else {
        window.location = `{{ route('user.create.unified', 'type=personal') }}`;
    }
}
</script>
@endpush
@endsection
