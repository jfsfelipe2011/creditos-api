<?php

namespace App\Repository;

class DefaultRepository implements RepositoryInterface
{
	private $modelClass;

	private $model;

	public function __construct($modelClass)
	{
		$this->model = new $modelClass;
	}

	public function all()
	{
		return $this->model->all();
	}

	public function create($data)
	{
		$this->model->fill($data);
		$this->model->save();
		return $this->model;
	}

	public function update($search, $data)
	{
		$model = $this->findInternal($search);
		$model->fill($data);
		$model->save();
		return $model;
	}

	public function delete($search)
	{
		$model = $this->findInternal($search);
		$model->delete();
	}

	public function find($id)
	{
		return $this->model->findOrFail($id);
	}

	public function findByField($field, $value)
	{
		return $this->model->where($field, '=', $value)->get();
	}

	public function findOneByField($field, $value)
	{
		return $this->model->where($field, '=', $value)->firstOrFail();
	}

	public function findOneByFields($search)
	{
		$queryBuilder = $this->model;

		foreach ($search as $field => $value) {
			$queryBuilder = $queryBuilder->where($field, '=', $value);
		}

		return $queryBuilder->firstOrFail();
	}

	protected function findInternal($search)
	{
		return is_array($search) ? $this->findOneByFields($search) : $this->find($search);
	}
}