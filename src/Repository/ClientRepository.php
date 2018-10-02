<?php

namespace App\Repository;

use App\Models\Client;

class ClientRepository extends DefaultRepository
{
	public function __construct()
	{
		parent::__construct(Client::class);
	}
}