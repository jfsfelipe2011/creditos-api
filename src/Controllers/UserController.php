<?php

namespace App\Controllers;

class UserController extends Controller
{
	public function index($request, $response)
	{	
		return $response->withJson($this->userRepository->all(), 200);
	}

	public function store($request, $response)
	{
		$data = $request->getParsedBody();
		$data['password'] = md5($data['password']);

		$insert = $this->userRepository->create($data);
		return $response->withJson($insert, 201);
	}

	public function update($request, $response, $args)
	{
		$data = $request->getParsedBody();
		$data['password'] = md5($data['password']);

		$update = $this->userRepository->update($args['id'], $data);

		return $response->withJson($update, 200);
	}

	public function delete($request, $response, $args)
	{
		$this->userRepository->delete($args['id']);
		return $response->withStatus(204);
	}

	public function show($request, $response, $args)
	{
		$user = $this->userRepository->find($args['id']);
		return $response->withJson($user, 200);
	}
}