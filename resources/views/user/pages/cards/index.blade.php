@extends('user.layouts.app')
@section('title','My Cards')

@section('content')
<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Your Business Cards</h5>
    <a href="{{ route('user.cards.create') }}" class="btn btn-primary btn-sm">New Card</a>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr>
          <th>Title</th><th>Slug</th><th>Public Link</th><th>Updated</th><th></th>
        </tr></thead>
        <tbody>
        @forelse($cards as $card)
          <tr>
            <td>{{ $card->title }}</td>
            <td>{{ $card->slug }}</td>
            <td>
              <a target="_blank" href="{{ route('cards.public', $card->slug) }}">
                {{ route('cards.public', $card->slug) }}
              </a>
            </td>
            <td>{{ $card->updated_at?->diffForHumans() }}</td>
            <td>
              <a class="btn btn-sm btn-outline-secondary" href="{{ route('user.cards.builder',$card->id) }}">
                Open Builder
              </a>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-muted p-4">No cards yet.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
