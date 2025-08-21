{{--
    Choose-a-Card UI (Bootstrap-ready)
    This view posts to route('user.cards.store'), which is defined in routes/user_cards.php.
    If you already have a layout, replace the <html> wrapper with @extends and @section.
--}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choose a Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5 page-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Create your card</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.cards.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="card_type" class="form-label">Card Type</label>
                            <select id="card_type" name="card_type" class="form-select" required>
                                <option value="" disabled selected>Pick a style...</option>
                                @foreach(($cardTypes ?? []) as $type)
                                    <option value="{{ $type['key'] }}">{{ $type['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Title (optional)</label>
                            <input id="title" name="title" type="text" class="form-control" placeholder="e.g., Mustafa’s Card">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Short description (optional)</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="What makes this card special?"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Continue</button>
                            <a href="{{ route('user.cards.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-muted small mt-3">
                Having trouble? Make sure <code>require base_path('routes/user_cards.php');</code> is added at the bottom of <code>routes/web.php</code>,
                and that <code>App\Http\Controllers\User\CardController</code> exists.
            </p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
