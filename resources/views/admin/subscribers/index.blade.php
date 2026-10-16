@extends('layouts.admin')

@section('title', 'Suscriptores')

@section('content')
  <div class="admin-head"><h1>Suscriptores</h1></div>

  <div class="panel">
    <table class="table">
      <thead>
        <tr>
          <th>Correo</th>
          <th>Fecha</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($subscribers as $subscriber)
          <tr>
            <td>{{ $subscriber->email }}</td>
            <td>{{ $subscriber->created_at->format('d/m/Y H:i') }}</td>
            <td class="table__actions">
              <form method="POST" action="{{ route('admin.subscribers.destroy', $subscriber) }}" onsubmit="return confirm('¿Eliminar este suscriptor?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="link link--danger">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="3" class="table__empty">Aún no hay suscriptores.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

@endsection
