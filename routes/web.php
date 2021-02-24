<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('user/register', 'UserController@store');
    $router->post('/reset-mail', 'UserController@resetEmail');
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/news', 'NewsController@store');
        $router->delete('/news', 'NewsController@destroy');
        $router->patch('/news', 'NewsController@update');
        $router->patch('/news/publish', 'NewsController@publish');
        $router->patch('/news/unpublish', 'NewsController@unpublish');
    });
    $router->get('/news/{news}', 'NewsController@show');
    $router->get('/news', 'NewsController@index');
});
