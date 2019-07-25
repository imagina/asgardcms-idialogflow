<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => '/contacts'], function (Router $router) {
  $router->post('/', [
    'as' => 'api.idialogflow.contacts.store',
    'uses' => 'ContactController@create',
    //'middleware' => ['auth:api']
  ]);
  $router->get('/', [
    'as' => 'api.idialogflow.contacts.index',
    'uses' => 'ContactController@index',

  ]);
  $router->get('/{bot}', [
    'as' => 'api.idialogflow.contacts.show',
    'uses' => 'ContactController@show',
    //'middleware' => ['auth:api']
  ]);
  $router->put('/{bot}', [
    'as' => 'api.idialogflow.contacts.update',
    'uses' => 'ContactController@update',
    //'middleware' => ['auth:api']
  ]);
  $router->delete('/{bot}', [
    'as' => 'api.idialogflow.contacts.destroy',
    'uses' => 'ContactController@delete',
    //'middleware' => ['auth:api']
  ]);
});
