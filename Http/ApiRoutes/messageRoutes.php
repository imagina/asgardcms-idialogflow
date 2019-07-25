<?php

use Illuminate\Routing\Router;

// Messages
$router->group(['prefix' => '/messages'], function (Router $router) {
  //$router->post('/', [
  // 'as' => 'api.idialogflow.messages.store',
  // 'uses' => 'MessageController@store',
  //'middleware' => ['auth:api']
  //]);
  $router->get('/', [
    'as' => 'api.idialogflow.messages.index',
    'uses' => 'MessageController@index',
  ]);
});
