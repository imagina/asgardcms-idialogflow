<?php

use Illuminate\Routing\Router;

// Bots
$router->group(['prefix' => '/bot-contact'], function (Router $router) {
  $router->post('/', [
    'as' => 'api.idialogflow.bot.contacts.store',
    'uses' => 'BotContactController@create',
    //'middleware' => ['auth:api']
  ]);
  $router->get('/', [
    'as' => 'api.idialogflow.bot.contacts.index',
    'uses' => 'BotContactController@index',

  ]);
  $router->get('/{bot}', [
    'as' => 'api.idialogflow.bot.contacts.show',
    'uses' => 'BotContactController@show',
    //'middleware' => ['auth:api']
  ]);
  $router->put('/{bot}', [
    'as' => 'api.idialogflow.bot.contacts.update',
    'uses' => 'BotContactController@update',
    //'middleware' => ['auth:api']
  ]);
  $router->delete('/{bot}', [
    'as' => 'api.idialogflow.bot.contacts.destroy',
    'uses' => 'BotContactController@delete',
    //'middleware' => ['auth:api']
  ]);
});
