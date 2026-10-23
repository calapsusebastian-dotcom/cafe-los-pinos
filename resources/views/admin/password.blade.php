@extends('layouts.admin')

@section('title', 'Mi contraseña')

@section('content')
  <div class="admin-head"><h1>Cambiar contraseña</h1></div>

  <form method="POST" action="{{ route('admin.password.update') }}" class="form">
    @csrf
    @method('PUT')

    <fieldset class="panel">
      <div class="field">
        <label for="current_password">Contraseña actual</label>
        <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
        @error('current_password')<p class="field__error">{{ $message }}</p>@enderror
      </div>

      <div class="field">
        <label for="password">Nueva contraseña</label>
        <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password">
        @error('password')<p class="field__error">{{ $message }}</p>@enderror
      </div>

      <div class="field">
        <label for="password_confirmation">Repetir nueva contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" autocomplete="new-password">
      </div>
    </fieldset>

    <button type="submit" class="btn-admin">Guardar contraseña</button>
  </form>
@endsection
