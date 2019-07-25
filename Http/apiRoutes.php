<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => '/idialogflow'], function (Router $router) {

  // Intents
  require('ApiRoutes/intentRoutes.php');

  // Bots
  require('ApiRoutes/botRoutes.php');

  // Contacts
  require('ApiRoutes/contactRoutes.php');

  // Bot Contacts
  require('ApiRoutes/botContactRoutes.php');

  // Messages
  require('ApiRoutes/messageRoutes.php');

});
