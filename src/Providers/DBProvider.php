<?php

namespace App\Providers;

use App\Models\Credit;
use App\Models\Extract;
use App\Models\User;
use App\Repository\ClientRepository;
use App\Repository\RepositoryFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

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

		$container['clientRepository'] = function ($container) {
			return new ClientRepository();
		};

		$container['creditRepository'] = function ($container) {
			$repository = new RepositoryFactory();
			return $repository->factory(Credit::class);
		};

		$container['extractRepository'] = function ($container) {
			$repository = new RepositoryFactory();
			return $repository->factory(Extract::class);
		};
	}
}