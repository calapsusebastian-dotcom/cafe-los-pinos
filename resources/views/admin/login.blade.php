<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Ingresar · Café Los Pinos</title>
  <link rel="icon" type="image/png" href="{{ asset('img/logo-header.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Source+Serif+4:wght@500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin admin--login">

  <form method="POST" action="{{ route('admin.login') }}" class="login">
    @csrf
    <img src="{{ asset('img/logo-header.png') }}" alt="Café Los Pinos" class="login__logo">
    <h1 class="login__title">Administrador</h1>

    <div class="field">
      <label for="email">Correo electrónico</label>
      <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
      @error('email')<p class="field__error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" required autocomplete="current-password">
      @error('password')<p class="field__error">{{ $message }}</p>@enderror
    </div>

    <label class="check">
      <input type="checkbox" name="remember" value="1"> Mantener la sesión iniciada
    </label>

    <button type="submit" class="btn-admin btn-admin--block">Ingresar</button>
  </form>

</body>
</html>
