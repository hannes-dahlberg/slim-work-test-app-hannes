<?php

require '../vendor/autoload.php';

//Register services
$container = new \Slim\Container;
//View service
$container['TemplateEngineService'] = function($container) {
    //Return blade service with template and cache folder location
    return new \Slim\Views\Blade(
        '../app/Views',
        '../cache'
    );
};
//Storage service
$container['StorageService'] = function($container) {
    //Return Store2File service and specify file location
    return new \HannesD\Store2File(
        '../data/recipes.txt'
    );
};

//Create app with container
$app = new \Slim\App($container);

//Controllers for recipes
$app->get('/recipes', '\App\Controllers\RecipesController:index');
$app->get('/recipes/create', '\App\Controllers\RecipesController:create');
$app->post('/recipes/', '\App\Controllers\RecipesController:store');
$app->get('/recipes/export', '\App\Controllers\RecipesController:export');
$app->get('/recipes/{name}', '\App\Controllers\RecipesController:show');
$app->get('/recipes/{name}/edit', '\App\Controllers\RecipesController:edit');
$app->put('/recipes/{name}', '\App\Controllers\RecipesController:update');
$app->delete('/recipes/{name}', '\App\Controllers\RecipesController:destroy');

//Run app!
$app->run();