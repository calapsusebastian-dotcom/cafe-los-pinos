@csrf

<div class="field">
  <label for="name">Nombre</label>
  <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required maxlength="120">
  @error('name')<p class="field__error">{{ $message }}</p>@enderror
</div>

<div class="field">
  <label for="description">Descripción</label>
  <textarea id="description" name="description" rows="3" required maxlength="500">{{ old('description', $product->description) }}</textarea>
  @error('description')<p class="field__error">{{ $message }}</p>@enderror
</div>

<div class="field-row">
  <div class="field">
    <label for="price">Precio (COP)</label>
    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required min="0" step="100">
    @error('price')<p class="field__error">{{ $message }}</p>@enderror
  </div>

  <div class="field">
    <label for="weight">Presentación</label>
    <input type="text" id="weight" name="weight" value="{{ old('weight', $product->weight) }}" required maxlength="40" placeholder="500 g">
    @error('weight')<p class="field__error">{{ $message }}</p>@enderror
  </div>

  <div class="field">
    <label for="sort_order">Orden</label>
    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}" min="0" placeholder="Al final">
    @error('sort_order')<p class="field__error">{{ $message }}</p>@enderror
  </div>
</div>

<div class="field">
  <label for="image">Foto del producto</label>
  @if ($product->exists)
    <img src="{{ $product->image_url }}" alt="" class="form-thumb">
  @endif
  <input type="file" id="image" name="image" accept="image/png,image/jpeg,image/webp" @required(! $product->exists)>
  <p class="muted">JPG, PNG o WebP, máximo 2 MB. @if ($product->exists) Déjala vacía para conservar la actual. @endif</p>
  @error('image')<p class="field__error">{{ $message }}</p>@enderror
</div>

<label class="check">
  <input type="checkbox" name="is_visible" value="1" @checked(old('is_visible', $product->is_visible))> Mostrar en la tienda
</label>

<div class="form-actions">
  <button type="submit" class="btn-admin">Guardar</button>
  <a href="{{ route('admin.products.index') }}" class="link">Cancelar</a>
</div>
