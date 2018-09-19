<?php

namespace App\Providers;

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Repository\RepositoryFactory;
use App\Models\User;

class DBProvider implements ProviderInterface
{
	public function __construct($container)
	{
		$this->register($container);
	}
	
	public function register($container)
	{
		$capsule = new Capsule();
		$capsule->addConnection($container->get('settings')['db']);
		$capsule->setAsGlobal();
		$capsule->bootEloquent();

		$container['userRepository'] = function ($container) {
			$repository = new RepositoryFactory();
			return $repository->factory(User::class);
		};
	}
}