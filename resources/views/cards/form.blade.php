@csrf
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Title</label>
    <input name="title" class="form-control" value="{{ old('title',$card->title ?? '') }}" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Subtitle</label>
    <input name="sub_title" class="form-control" value="{{ old('sub_title',$card->sub_title ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Type</label>
    <select name="type" class="form-select">
      <option value="business" @selected(old('type',$card->type ?? '')==='business')>Business</option>
      <option value="personal" @selected(old('type',$card->type ?? '')==='personal')>Personal</option>
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">Language</label>
    <input name="card_lang" class="form-control" value="{{ old('card_lang',$card->card_lang ?? 'en') }}">
  </div>

  <div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4">{!! old('description',$card->description ?? '') !!}</textarea>
  </div>

  <div class="col-md-6">
    <label class="form-label">Cover (image URL or upload)</label>
    <input name="cover" class="form-control" value="{{ old('cover',$card->cover ?? '') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Profile Image</label>
    <input type="file" name="profile" class="form-control">
  </div>

  <div class="col-md-6">
    <label class="form-label">Card URL (slug)</label>
    <input name="card_url" class="form-control" value="{{ old('card_url',$card->card_url ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Status</label>
    <select name="card_status" class="form-select">
      <option value="activated" @selected(old('card_status',$card->card_status ?? '')==='activated')>Activated</option>
      <option value="deleted" @selected(old('card_status',$card->card_status ?? '')==='deleted')>Deleted</option>
    </select>
  </div>

  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
    <a href="{{ route('cards.index') }}" class="btn btn-outline-secondary">Cancel</a>
  </div>
</div>
