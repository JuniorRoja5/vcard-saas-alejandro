<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Dashboard') · {{ config('app.name') }}</title>

  {{-- Bootstrap (CDN) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Icons (Bootstrap Icons) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  @stack('styles')
  <style>
    /* Tiny utility tweaks */
    .table-actions .btn { padding: .25rem .5rem; }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name','GoBiz') }}</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div id="topnav" class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto mb-2">
          <li class="nav-item"><a class="nav-link" href="{{ route('cards.index') }}">Cards</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('galleries.index') }}">Gallery</a></li>
        </ul>
        <div class="d-flex gap-2">
          @auth
            <span class="navbar-text">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">@csrf
              <button class="btn btn-outline-secondary btn-sm">Logout</button>
            </form>
          @endauth
        </div>
      </div>
    </div>
  </nav>

  <main class="container py-4">
    @include('partials.flash')
    @yield('content')
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
