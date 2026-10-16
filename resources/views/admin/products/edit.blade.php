@extends('layouts.admin')

@section('title', 'Editar producto')

@section('content')
  <div class="admin-head"><h1>Editar {{ $product->name }}</h1></div>

  <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="panel form">
    @method('PUT')
    @include('admin.products._form')
  </form>
@endsection
