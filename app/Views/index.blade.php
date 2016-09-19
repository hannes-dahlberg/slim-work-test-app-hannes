@extends('layout');
@section('content')
    <h1>Recipes</h1>
    <table class="table tab-striped">
        <thead>
            <tr>
                <th class="col-xs-5">Name</th>
                <th class="col-xs-4">Creator</th>
                <th class="col-xs-3"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($recipes as $recipe)
                <tr>
                    <td>{{ $recipe['name'] }}</td>
                    <td>{{ $recipe['creator'] }}</td>
                    <td>
                        <a href="/recipes/{{ $recipe['name'] }}">Show</a>
                        <a href="/recipes/{{ $recipe['name'] }}/edit">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a class="btn btn-primary" href="/recipes/create">Add New Recipe</a>
    <div class="col-xs-12 text-center">
        <a href="/recipes/export" class="btn btn-lg btn-default">Export</a>
    </div>
@endsection
