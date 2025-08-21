<form action="{{ $route }}" method="POST" enctype="multipart/form-data" id="card-form">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Title</label>
            <input name="title" class="form-control" value="{{ old('title', $card->title ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Slug *</label>
            <input name="slug" class="form-control" required value="{{ old('slug', $card->slug ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Name</label>
            <input name="name" class="form-control" value="{{ old('name', $card->name ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Job Title</label>
            <input name="job_title" class="form-control" value="{{ old('job_title', $card->job_title ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Company</label>
            <input name="company" class="form-control" value="{{ old('company', $card->company ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Theme</label>
            <input name="theme" class="form-control" value="{{ old('theme', $card->theme ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input name="phone" class="form-control" value="{{ old('phone', $card->phone ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" value="{{ old('email', $card->email ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Website</label>
            <input name="website" type="url" class="form-control" value="{{ old('website', $card->website ?? '') }}">
        </div>
        <div class="col-12">
            <label class="form-label">Bio</label>
            <textarea name="bio" class="form-control" rows="3">{{ old('bio', $card->bio ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">Avatar</label>
            <input type="file" name="avatar" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Cover</label>
            <input type="file" name="cover" class="form-control">
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">Save</button>
        @if($card)
            <a class="btn btn-outline-secondary" href="{{ route('user.cards.preview', $card) }}" target="_blank">Preview</a>
        @endif
    </div>
</form>
