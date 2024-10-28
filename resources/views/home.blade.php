@extends('layouts/layout')

@section('title', 'Home')

@section('content')
<div>
    <h1 class="mb-4">Hello, {{ Auth::user()->name }}!</h1>
    <p>Email: {{ Auth::user()->email }}</p>
</div>

@endsection