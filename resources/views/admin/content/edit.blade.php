@extends('layouts.admin')

@section('title', 'Textos del sitio')

@section('content')
  <div class="admin-head"><h1>Textos del sitio</h1></div>

  <form method="POST" action="{{ route('admin.content.update') }}" class="form">
    @csrf
    @method('PUT')

    @foreach ($sections as $section)
      <fieldset class="panel">
        <legend>{{ $section['label'] }}</legend>

        @foreach ($section['fields'] as $key => $field)
          <div class="field">
            <label for="{{ $key }}">{{ $field['label'] }}</label>
            @if ($field['type'] === 'textarea')
              <textarea id="{{ $key }}" name="{{ $key }}" rows="3" required maxlength="600">{{ old($key, $values[$key]) }}</textarea>
            @else
              <input type="text" id="{{ $key }}" name="{{ $key }}" value="{{ old($key, $values[$key]) }}" required maxlength="600">
            @endif
            @error($key)<p class="field__error">{{ $message }}</p>@enderror
          </div>
        @endforeach
      </fieldset>
    @endforeach

    <div class="form-actions form-actions--sticky">
      <button type="submit" class="btn-admin">Guardar cambios</button>
    </div>
  </form>
@endsection
