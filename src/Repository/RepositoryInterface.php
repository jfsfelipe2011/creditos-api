<?php

namespace App\Repository;

interface RepositoryInterface
{
	public function all();

	public function create($data);

	public function update($search, $data);

	public function delete($search);

	public function find($id);

	public function findByField($field, $value);

	public function findOneByField($field, $value);

	public function findOneByFields($search);
}