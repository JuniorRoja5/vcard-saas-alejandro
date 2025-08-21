@extends('layouts.app')

@section('title','Cards')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Business Cards</h1>
  <a href="{{ route('cards.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg"></i> New Card
  </a>
</div>

<form method="GET" class="row g-2 align-items-end mb-3">
  <div class="col-md-4">
    <label class="form-label">Search</label>
    <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Title, user, slug...">
  </div>
  <div class="col-md-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      <option value="">Any</option>
      <option value="activated" @selected(request('status')==='activated')>Activated</option>
      <option value="deleted" @selected(request('status')==='deleted')>Deleted</option>
    </select>
  </div>
  <div class="col-md-3">
    <button class="btn btn-outline-secondary"><i class="bi bi-search"></i> Filter</button>
  </div>
</form>

<div class="table-responsive">
  <table class="table align-middle">
    <thead>
      <tr>
        <th>Title</th>
        <th>User</th>
        <th>Slug/URL</th>
        <th>Status</th>
        <th class="text-end">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($cards as $card)
        <tr>
          <td class="fw-medium">{{ $card->title }}</td>
          <td>{{ $card->user?->name ?? $card->user_id }}</td>
          <td><code>{{ $card->card_url }}</code></td>
          <td><span class="badge rounded-pill text-bg-{{ $card->card_status === 'activated' ? 'success':'secondary' }}">
            {{ ucfirst($card->card_status) }}</span></td>
          <td class="text-end table-actions">
            <a href="{{ route('cards.show',$card->id) }}" class="btn btn-light border"><i class="bi bi-eye"></i></a>
            <a href="{{ route('cards.edit',$card->id) }}" class="btn btn-light border"><i class="bi bi-pencil"></i></a>
            <form action="{{ route('cards.destroy',$card->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Delete this card?')">
              @csrf @method('DELETE')
              <button class="btn btn-light border"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-muted">No cards found.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $cards->withQueryString()->links() }}
@endsection
