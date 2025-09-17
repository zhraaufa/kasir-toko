@props(['name', 'options', 'value' => null])
@php
$val = old($name, $value);
@endphp

<select name="{{ $name }}" class="form-control @error($name) is-invalid @enderror">
    @foreach ($options as $option)
        <option value="{{ $option[0] }}" {{ $val == $option[0] ? 'selected' : '' }}>
            {{ $option[1] }}
        </option>
    @endforeach
</select>

@error($name)
<div class="invalid-feedback">
    {{ $message }}
</div>
@enderror
