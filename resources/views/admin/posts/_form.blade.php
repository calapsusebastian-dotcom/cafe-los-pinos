@csrf

<div class="field">
  <label for="title">Título</label>
  <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required maxlength="150">
  @error('title')<p class="field__error">{{ $message }}</p>@enderror
</div>

<div class="field">
  <label for="slug">Dirección web</label>
  <input type="text" id="slug" name="slug" value="{{ old('slug', $post->slug) }}" maxlength="160" placeholder="Se genera sola a partir del título">
  <p class="muted">Solo letras, números y guiones. Cámbiala con cuidado: los enlaces anteriores dejarán de funcionar.</p>
  @error('slug')<p class="field__error">{{ $message }}</p>@enderror
</div>

<div class="field">
  <label for="excerpt">Resumen (aparece en Google)</label>
  <textarea id="excerpt" name="excerpt" rows="3" required maxlength="300">{{ old('excerpt', $post->excerpt) }}</textarea>
  <p class="muted">Máximo 300 caracteres. Intenta que explique de qué trata el artículo en una o dos frases.</p>
  @error('excerpt')<p class="field__error">{{ $message }}</p>@enderror
</div>

<div class="field">
  <label for="body">Contenido</label>
  <textarea id="body" name="body" rows="18" required>{{ old('body', $post->body) }}</textarea>
  <p class="muted">Usa <code>## Subtítulo</code> para títulos, <code>**negrita**</code>, <code>- elemento</code> para listas y una línea en blanco entre párrafos.</p>
  @error('body')<p class="field__error">{{ $message }}</p>@enderror
</div>

<div class="field-row">
  <div class="field">
    <label for="published_at">Fecha de publicación</label>
    <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
    <p class="muted">Si es futura, el artículo se publicará solo ese día.</p>
    @error('published_at')<p class="field__error">{{ $message }}</p>@enderror
  </div>
</div>

<label class="check">
  <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published))> Publicar (si lo desmarcas queda como borrador)
</label>

<div class="form-actions">
  <button type="submit" class="btn-admin">Guardar</button>
  <a href="{{ route('admin.posts.index') }}" class="link">Cancelar</a>
</div>
