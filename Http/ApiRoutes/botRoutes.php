<?php

use Illuminate\Routing\Router;

// Bots
$router->group(['prefix' => '/bots'], function (Router $router) {
  $router->post('/', [
    'as' => 'api.idialogflow.bots.store',
    'uses' => 'BotController@create',
    'middleware' => ['auth:api']
  ]);
  $router->get('/', [
    'as' => 'api.idialogflow.bots.index',
    'uses' => 'BotController@index',

  ]);
  $router->get('/{bot}', [
    'as' => 'api.idialogflow.bots.show',
    'uses' => 'BotController@show',
    'middleware' => ['auth:api']
  ]);
  $router->put('/{bot}', [
    'as' => 'api.idialogflow.bots.update',
    'uses' => 'BotController@update',
    'middleware' => ['auth:api']
  ]);
  $router->delete('/{bot}', [
    'as' => 'api.idialogflow.bots.destroy',
    'uses' => 'BotController@delete',
    'middleware' => ['auth:api']
  ]);
});
