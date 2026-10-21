@extends('layouts.admin')

@section('title', 'Textos del sitio')

@section('content')
  <div class="admin-head"><h1>Textos del sitio</h1></div>

  <form method="POST" action="{{ route('admin.content.update') }}" class="form" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <fieldset class="panel">
      <legend>Imágenes del sitio</legend>
      <p class="muted">Cada foto aparece en el lugar indicado. Si no subes una, se queda la actual. JPG, PNG o WebP, máximo 4 MB.</p>

      @foreach ($images as $key => $image)
        <div class="field">
          <label for="{{ $key }}">{{ $image['label'] }}</label>
          <img src="{{ $imageUrls[$key] }}" alt="" class="form-thumb form-thumb--wide">
          <input type="file" id="{{ $key }}" name="{{ $key }}" accept="image/png,image/jpeg,image/webp">
          <p class="muted">{{ $image['hint'] }}</p>
          @if (in_array($key, $uploadedImages, true))
            <label class="check"><input type="checkbox" name="remove_{{ $key }}" value="1"> Quitar mi foto y volver a la original</label>
          @endif
          @error($key)<p class="field__error">{{ $message }}</p>@enderror
        </div>
      @endforeach
    </fieldset>

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
