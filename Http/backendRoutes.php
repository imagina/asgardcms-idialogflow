<?php

Route::group(['middleware' => 'web', 'prefix' => 'idialogflow', 'namespace' => 'Modules\Idialogflow\Http\Controllers'], function()
{
  Route::get('/', 'IdialogflowController@index');
});
