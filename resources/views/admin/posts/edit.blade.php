@extends('layouts.admin')

@section('title', 'Editar artículo')

@section('content')
  <div class="admin-head"><h1>Editar {{ $post->title }}</h1></div>

  <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="panel form">
    @method('PUT')
    @include('admin.posts._form')
  </form>
@endsection
