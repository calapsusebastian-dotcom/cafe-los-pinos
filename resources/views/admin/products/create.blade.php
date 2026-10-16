@extends('layouts.admin')

@section('title', 'Nuevo producto')

@section('content')
  <div class="admin-head"><h1>Nuevo producto</h1></div>

  <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="panel form">
    @include('admin.products._form')
  </form>
@endsection
