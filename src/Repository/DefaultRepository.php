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

	public function update($id, $data)
	{
		$model = $this->find($id);
		$model->fill($data);
		$model->save();
		return $model;
	}

	public function delete($id)
	{
		$model = $this->find($id);
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
}