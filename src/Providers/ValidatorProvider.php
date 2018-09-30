<?php

namespace App\Providers;

use Awurth\SlimValidation\Validator;

class ValidatorProvider implements ProviderInterface
{
	public function __construct($container)
	{
		$this->register($container);
	}

	public function register($container)
	{
		$container['validator'] = function () {
			return new Validator();
		};
	}
}