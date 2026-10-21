@extends('layouts.admin')

@section('title', 'Nuevo artículo')

@section('content')
  <div class="admin-head"><h1>Nuevo artículo</h1></div>

  <form method="POST" action="{{ route('admin.posts.store') }}" class="panel form" enctype="multipart/form-data">
    @include('admin.posts._form')
  </form>
@endsection
