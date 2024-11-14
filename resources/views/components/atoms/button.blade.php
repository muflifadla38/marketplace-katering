@props(['type' => 'button', 'color' => 'primary'])

<button type="{{ $type }}" {{ $attributes->class(['btn'])->merge(['class' => 'btn btn-' . $color]) }}>
  {{ $slot }}
</button>
