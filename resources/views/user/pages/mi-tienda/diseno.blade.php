@extends('user.layouts.index', ['header'=>true,'nav'=>true,'demo'=>true,'settings'=>$settings])

@section('content')
<div class="page-wrapper">
  <div class="page-body">
    <div class="container-fluid" style="padding: 0; max-width: none !important; margin: 0;">
      <iframe
        id="disenoFrame"
        src="{{ asset('mi-tienda/diseno.html') }}"
        style="width: 100%; height: calc(100vh - 80px); border: 0; display: block; overflow-x: auto;"
        referrerpolicy="no-referrer"
      ></iframe>
    </div>
  </div>
</div>
@endsection