@props(['items', 'property' => 'name', 'select2' => 'true'])

<select {{ $attributes->merge(['class' => 'form-select form-select-solid']) }} data-kt-select2="{{ $select2 }}">
  <option></option>
  @foreach ($items as $item)
    <option value="{{ $item?->$property ?? $item }}">{{ ucfirst($item?->$property ?? $item) }}
    </option>
  @endforeach
</select>
