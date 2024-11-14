@php
  $segments = str_replace('-', ' ', Request::segments());
@endphp

<ul class="pt-1 my-0 breadcrumb breadcrumb-dot fw-semibold fs-7">
  <li class="breadcrumb-item">
    <a href="{{ route('dashboard.index') }}">Dashboard</a>
  </li>

  @foreach ($segments as $index => $segment)
    <li class="breadcrumb-item text-capitalize @if ($index === count($segments) - 1) text-muted @endif">
      {{ $segment !== 'dashboard' ? $segment : 'Home' }}
    </li>
  @endforeach
</ul>
