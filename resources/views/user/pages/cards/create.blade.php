@extends('user.layouts.index', ['header'=>true,'nav'=>true,'demo'=>true,'settings'=>$settings])
@section('content')
<div class="page-wrapper">
  <div class="page-header d-print-none">
    <div class="container-fluid"><h2 class="page-title">{{ __('Create Card') }}</h2></div>
  </div>
  <div class="page-body">
    <div class="container-fluid">
      <div class="card"><div class="card-body">
        <form method="POST" action="{{ route('user.cards.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">{{ __('Title') }}</label>
            <input type="text" class="form-control" name="title" placeholder="My Business Card">
          </div>
          <button class="btn btn-primary" type="submit">{{ __('Create') }}</button>
        </form>
      </div></div>
    </div>
  </div>
</div>
@endsection
