<?php namespace App\Controllers;

use \Interop\Container\ContainerInterface as ContainerInterface;

class RecipesController {
    protected $ci;
    protected $storageService;
    protected $templateEngineService;

    /**
     * RecipesController constructor.
     *
     * Sets the template and storage services
     * @param ContainerInterface $ci Container interface passed by Slim
     */
    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
        $this->templateEngineService = $this->ci->TemplateEngineService;
        $this->storageService = $this->ci->StorageService;
    }

    /**
     * Converts data array to an associated array. Used for listing recipes
     *
     * @param $data Array of data with numeric indices
     * @return Array The converted assoicated array. Data array with named keys
     */
    private function getAssociated($data) {
        foreach($data as &$item) {
            $item = [
                'name' => $item[0],
                'ingredients' => $item[1],
                'creator' => $item[2]

            ];
        }
        return $data;
    }

    /**
     * Fetch all recipes and return the index view while passing the fetched recipes
     *
     * @param $request
     * @param $response
     * @param $next
     * @return mixed
     */
    public function index($request, $response, $next) {
        //Get all recipes
        $recipes = $this->getAssociated($this->storageService->get());
        //Renders the index view with recipes
        return $this->templateEngineService->render($response, 'index', ['recipes' => $recipes]);
    }

    /**
     * Create new recipe view
     *
     * @param $request
     * @param $response
     * @param $next
     * @return mixed
     */
    public function create($request, $response, $next) {
        //Render create view with an empty recipe
        return $this->templateEngineService->render($response, 'create', ['recipe' => [
            'name' => '',
            'ingredients' => '',
            'creator' => ''
        ]]);
    }

    /**
     * Save a new recipe
     *
     * @param $request
     * @param $response
     * @param $next
     * @return mixed
     */
    public function store($request, $response, $next) {
        //Get the post data from request (posted recipe data)
        $data = array_values($request->getParsedBody());
        //If the recipe can't be found, redirect back to the create view
        if($this->storageService->get($data[0])['index'] != -1) {
            return $response->withStatus(302)->withHeader('Location', '/recipes/create');
        }

        //Creates new recipe
        $this->storageService->create($data);

        //Redirect bac to recipe index view
        return $response->withStatus(302)->withHeader('Location', '/recipes');
    }

    /**
     * Show a recipe
     *
     * @param $request
     * @param $response
     * @param $next
     * @return mixed
     */
    public function show($request, $response, $next) {
        //Get the recipe name from url
        $name = $request->getAttribute('name');
        //Get the recipe from storage using name
        $recipe = $this->storageService->get($name);
        //If recipe was not found (index is -1) redirect back to recipe list
        if($recipe['index'] == -1) {
            return $response->withStatus(302)->withHeader('Location', '/recipes');
        }
        //Convert to an associate array
        $recipe = $this->getAssociated([$recipe['value']])[0];
        //Render show view with fetched recipe
        return $this->templateEngineService->render($response, 'show', ['recipe' => $recipe]);
    }

    /**
     * Show edit form for a recipe
     *
     * @param $request
     * @param $response
     * @param $next
     * @return mixed
     */
    public function edit($request, $response, $next) {
        //Get the recipe name from url
        $name = $request->getAttribute('name');
        //Get recipe from storage using name
        $recipe = $this->storageService->get($name);
        //If recipe was not found (index is -1) redirect back to recipe list
        if($recipe['index'] == -1) {
            return $response->withStatus(302)->withHeader('Location', '/recipes');
        }
        //Convert to an associate array
        $recipe = $this->getAssociated([$recipe['value']])[0];
        //Render edit view with fetch recipe
        return $this->templateEngineService->render($response, 'edit', ['recipe' => $recipe]);
    }

    /**
     * Post request updating a recipe
     *
     * @param $request
     * @param $response
     * @param $next
     * @return mixed
     */
    public function update($request, $response, $next) {
        //Get the recipe name from url
        $name = $request->getAttribute('name');
        //Get the post data from form
        $data = array_values($request->getParsedBody());
        //Remove the first value from form because it's a reference to method
        array_splice($data, 0, 1);

        //Get the current recipe (the one we're trying to update)
        $current = $this->storageService->get($name);
        //Get any recipe using the same name as the new name provided (from post data)
        $check = $this->storageService->get($data[0]);

        //If recipe was not found (index is -1) redirect back to recipe list
        if($current['index'] == -1) {
            return $response->withStatus(302)->withHeader('Location', '/recipes');
        }

        /*Check if user is trying to change recipe name to an already existing recipe
        (sharing the same name). This will redirect back to edit page*/
        if($check['index'] != -1 && $current['index'] != $check['index']) {
            return $response->withStatus(302)->withHeader('Location', '/recipes/'. $name. '/edit');
        }

        //Update recipe
        $this->storageService->update($name, $data);

        //Redirect back to recipes view
        return $response->withStatus(302)->withHeader('Location', '/recipes');
    }

    /**
     * Delete a recipe
     *
     * @param $request
     * @param $response
     * @param $next
     * @return mixed
     */
    public function destroy($request, $response, $next) {
        //Get the recipe name from url
        $name = $request->getAttribute('name');
        //Delete recipe using name
        $this->storageService->delete($name);

        //Redirect back to recipes index view
        return $response->withStatus(302)->withHeader('Location', '/recipes');
    }

    /**
     * Export recipes to JSON data
     *
     * @return string
     */
    public function export() {
        //Get all recipes
        $recipes = $this->getAssociated($this->storageService->get());
        //Encode to json
        return json_encode($recipes);
    }
}