<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();

/**
 * One recipe can have:
 * 
 * - name
 * - ingredients
 * - creator
 *
 * Your task: see TODOs and make this work (and make it better). Do this however you like (classes, libs etc)
 * but the storage method must be file based (as it is now).
 * 
 * Keep it simple but elegant!
 */

$app->get('/', function () {
    // TODO: show all recipes
    // TODO: make support for ?order=asc and ?order=desc to reverse order of recipes
    echo 'No recipes!';
});

$app->get('/recipes/:name', function ($name) {
    // TODO: show one recipe
});

$app->post('/recipes/add', function () {

	// TODO: wait, this just overwrites all recipes everytime.. Make better (and also safer..)!
	file_put_contents('recipes.txt', implode(";", [
    	'name' => $_POST['name'],
    	'ingredients' => $_POST['ingredients'],
    	'creator' => $_POST['creator']
	]));
});

$app->get('/json', function ($name) {
    // TODO: export all recipes as a JSON file with all properties
});



$app->run();

