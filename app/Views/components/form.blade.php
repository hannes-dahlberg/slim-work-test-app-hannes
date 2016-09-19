<form method="post" action="/{{ 'recipes/'. ($recipe[name]) }}">
    @if($recipe['name'])
        <input type="hidden" name="_METHOD" value="PUT"/>
    @endif
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $recipe['name'] }}" />
    </div>
    <div class="form-group">
        <label for="ingredients">Ingredients</label>
        <input type="text" class="form-control" id="ingredients" name="ingredients" value="{{ $recipe['ingredients'] }}" />
    </div>
    <div class="form-group">
        <label for="creator">Creator</label>
        <input type="text" class="form-control" id="creator" name="creator" value="{{ $recipe['creator'] }}" />
    </div>
    <button type="submit" class="btn btn-primary">{{ ($recipe['name'] ? 'Save Existing' : 'Add New') }}</button>
</form>