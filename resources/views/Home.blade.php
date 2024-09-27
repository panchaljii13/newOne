@extends('Layout')
@section('title', 'Home')
@section('content')

@include('include.Header')
<h1>welcome</h1>
<h1></h1>
<p>hello</p>
<h1>welcome</h1>
@auth
<h1>{{auth()->user()->name}}</h1>
@endauth
@endsection