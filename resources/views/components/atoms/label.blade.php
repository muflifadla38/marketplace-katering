@props(['value', 'required' => true])

<label {{ $attributes->class(['required' => (bool) $required])->merge(['class' => 'form-label']) }}>
  {{ $value ?? $slot }}
</label>
