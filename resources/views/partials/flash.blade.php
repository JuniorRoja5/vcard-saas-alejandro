@foreach (['success','info','warning','danger'] as $t)
  @if(session($t))
    <div class="alert alert-{{ $t }} alert-dismissible fade show" role="alert">
      {{ session($t) }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
@endforeach
@if ($errors->any())
  <div class="alert alert-danger">
    <strong>Whoops!</strong> Please fix the errors below.
  </div>
@endif
