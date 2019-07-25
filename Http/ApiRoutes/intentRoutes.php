<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => '/intents'], function (Router $router) {
  $router->post('/', [
    'as' => 'api.idialogflow.intents.store',
    'uses' => 'IntentController@store',
  ]);
  $router->get('/', [
    'as' => 'api.idialogflow.intents.index',
    'uses' => 'IntentController@index',
  ]);
  $router->get('/{intentId}', [
    'as' => 'api.idialogflow.intents.show',
    'uses' => 'IntentController@show',
  ]);
  $router->put('/{intentId}', [
    'as' => 'api.idialogflow.intents.update',
    'uses' => 'IntentController@update',
  ]);
  $router->delete('/{intentId}/{project}', [
    'as' => 'api.idialogflow.intents.destroy',
    'uses' => 'IntentController@destroy',
  ]);
});
