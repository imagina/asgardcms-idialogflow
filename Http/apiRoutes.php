<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => '/idialogflow'], function (Router $router) {
  // Intents
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
    $router->delete('/{intentId}', [
      'as' => 'api.idialogflow.intents.destroy',
      'uses' => 'IntentController@destroy',
    ]);
  });

  // Bots
  $router->group(['prefix' => '/bots'], function (Router $router) {
    $router->post('/', [
      'as' => 'api.idialogflow.bots.store',
      'uses' => 'BotController@store',
    ]);
    $router->get('/', [
      'as' => 'api.idialogflow.bots.index',
      'uses' => 'BotController@index',
    ]);
    $router->get('/{bot}', [
      'as' => 'api.idialogflow.bots.show',
      'uses' => 'BotController@show',
    ]);
    $router->put('/{bot}', [
      'as' => 'api.idialogflow.bots.update',
      'uses' => 'BotController@update',
    ]);
    $router->delete('/{bot}', [
      'as' => 'api.idialogflow.bots.destroy',
      'uses' => 'BotController@destroy',
    ]);
  });

});
