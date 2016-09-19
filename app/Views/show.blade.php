@extends('layout');
@section('content')
    <h1>Recipe: {{ $recipe['name'] }}</h1>
    <p>{{ $recipe['ingredients'] }}</p>
    <em>Creator: {{ $recipe['creator'] }}</em>
@endsection
