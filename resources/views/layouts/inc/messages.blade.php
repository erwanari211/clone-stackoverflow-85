@if ($errors->any())
    <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
        {{ $error }}<br>
      @endforeach
    </div>
  @endif

  @if (session()->has('successMessage'))
    <div class="alert alert-success">
      {{ session('successMessage') }}
    </div>
  @endif
