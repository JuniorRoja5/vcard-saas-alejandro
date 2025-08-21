@extends('user.layouts.index', ['header'=>true,'nav'=>true,'demo'=>true,'settings'=>$settings])

@section('content')
<div class="page-wrapper">
  <div class="page-header d-print-none">
    <div class="container-fluid">
      <h2 class="page-title">{{ __('Business Card Builder') }}</h2>
    </div>
  </div>

  <div class="page-body">
    <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">{{ __('Builder') }}</h5>
          @php
              try { $backUrl = route('user.cards.index'); }
              catch (\Throwable $e) { $backUrl = url('/user/cards'); }
          @endphp
          <a href="{{ $backUrl }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
        </div>
        <div class="card-body p-0" style="height: calc(100vh - 320px);">
          <iframe
            id="builderFrame"
            src="{{ asset('mi-tienda/index.html') }}?card_id={{ $cardId }}"
            style="width:100%;height:100%;border:0;"
            referrerpolicy="no-referrer"
          ></iframe>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection