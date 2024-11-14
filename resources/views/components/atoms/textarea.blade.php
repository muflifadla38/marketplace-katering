@props(['rows' => 3])

<textarea {{ $attributes->merge(['class' => 'form-control']) }} rows="{{ $rows }}">
</textarea>
