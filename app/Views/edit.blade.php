@extends('layout')
@section('content')
    <h1>Edit recipe "<em>{{ $recipe['name'] }}</em>"</h1>
    @include('components.form')
    <form method="post" action="/recipes/{{ $recipe['name'] }}">
        <input type="hidden" name="_METHOD" value="DELETE"/>
        <button type="submit" class="btn btn-warning">Delete Recipe</button>
    </form>
@endsection