{{-- resources/views/cards/edit.blade.php --}}
@extends('layouts.app')
@section('title','Edit Card')
@section('content')
  <h1 class="h3 mb-3">Edit Card</h1>
  <form method="POST" action="{{ route('cards.update',$card->id) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('cards.form')
  </form>
@endsection
