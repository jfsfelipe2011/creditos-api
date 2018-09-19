<?php

namespace App\ResourceRouter;

class Resource
{
	public static function createBaseRoute($name, &$app)
	{


		$app->get('/' . $name, 'App\Controllers\UserController:index');
		$app->post('/'. $name, 'App\Controllers\UserController:store');
		$app->put('/' . $name . '/{id}', 'App\Controllers\UserController:update');
		$app->delete('/' . $name . '/{id}', 'App\Controllers\UserController:delete');
		$app->get('/' . $name . '/{id}', 'App\Controllers\UserController:show');

		return $app;
	}
}