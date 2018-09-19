<?php

namespace App\ResourceRouter;

class Resource
{
	public static function createBaseRoute($name, &$app)
	{
        $controller = 'App\Controllers\\' . ucfirst($name) . 'Controller';

		$app->get('/' . $name, $controller . ':index');
		$app->post('/'. $name, $controller . ':store');
		$app->put('/' . $name . '/{id}', $controller . ':update');
		$app->delete('/' . $name . '/{id}', $controller . ':delete');
		$app->get('/' . $name . '/{id}', $controller . ':show');

		return $app;
	}
}