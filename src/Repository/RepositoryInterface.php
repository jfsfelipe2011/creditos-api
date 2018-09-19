<?php

namespace App\Repository;

interface RepositoryInterface
{
	public function all();

	public function create($data);

	public function update($id, $data);

	public function delete($id);

	public function find($id);
}