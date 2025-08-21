{{-- resources/views/cards/create.blade.php --}}
@extends('layouts.app')
@section('title','New Card')
@section('content')
  <h1 class="h3 mb-3">New Card</h1>
  <form method="POST" action="{{ route('cards.store') }}" enctype="multipart/form-data">
    @include('cards.form')
  </form>
@endsection
