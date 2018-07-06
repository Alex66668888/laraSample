@foreach (['danger', 'warning', 'success', 'info'] as $msg)
  <!--session()->has($msg)判断session中$msg键对应的值是否为空-->
  @if(session()->has($msg))
    <div class="flash-message">
      <p class="alert alert-{{ $msg }}">
        {{ session()->get($msg) }}
      </p>
    </div>
  @endif
@endforeach