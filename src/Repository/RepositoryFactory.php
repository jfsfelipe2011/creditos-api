<?php

namespace App\Repository;

class RepositoryFactory
{
	public static function factory($modelClass)
	{
		return new DefaultRepository($modelClass);
	}
}