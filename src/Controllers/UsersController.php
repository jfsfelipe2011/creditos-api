<?php

namespace App\Controllers;

use App\Validation\UsersValidation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class UsersController extends Controller
{
	public function index($request, $response)
	{	
		$this->logger->info('Listado todos os usuários pelo usuário Admin');

		return $response->withJson($this->userRepository->all(), 200);
	}

	public function store($request, $response)
	{
		$data = $request->getParsedBody();
		$data['password'] = md5($data['password']);

		$validation = UsersValidation::validate($request, $this->validator);

		if (!$validation->isValid()) {
			$this->errorLogger->error('Erro na validação de Usuário', [
				'Erros' => $validation->getErrors()
			]);

			return $response->withJson($validation->getErrors(), 400);
		}

		try {
			$insert = $this->userRepository->create($data);
		} catch (QueryException $e) {
			$this->errorLogger->error('Erro ao inserir o usuário', [
				'erros' => $e->getMessage()
			]);

			return $response->withJson('Erro ao criar usuário', 400);
		} 

		$this->logger->info('Usuário criado com sucesso', [
			'data' => $insert
		]);

		return $response->withJson($insert, 201);
	}

	public function update($request, $response, $args)
	{
		$data = $request->getParsedBody();
		$data['password'] = md5($data['password']);

		$validation = UsersValidation::validate($request, $this->validator);

		if (!$validation->isValid()) {
			$this->errorLogger->error('Erro na validação de Usuário', [
				'Erros' => $validation->getErrors()
			]);

			return $response->withJson($validation->getErrors(), 400);
		}

		try {
			$update = $this->userRepository->update($args['id'], $data);	
		} catch (QueryException $e) {
			$this->errorLogger->error('Erro ao atualizar o usuário', [
				'erros' => $e->getMessage()
			]);

			return $response->withJson('Erro ao editar usuário', 400);
		} catch (ModelNotFoundException $e) {
			$this->errorLogger->error('Usuário de id ' . $args['id'] . ' não encontrado na base de dados');

			return $response->withJson('Usuário não encontrado', 404);
		}

		$this->logger->info('Usuário atualizado com sucesso', [
			'data' => $update
		]);

		return $response->withJson($update, 200);
	}

	public function delete($request, $response, $args)
	{
		try {
			$this->userRepository->delete($args['id']);	
		} catch (QueryException $e) {
			$this->errorLogger->error('Erro ao excluir o usuário', [
				'erros' => $e->getMessage()
			]);

			return $response->withJson('Erro ao excluir usuário', 400);
		} catch (ModelNotFoundException $e) {
			$this->errorLogger->error('Usuário de id ' . $args['id'] . ' não encontrado na base de dados');

			return $response->withJson('Usuário não encontrado', 404);
		}

		$this->logger->info('Usuário de id '. $args['id'] .' excluido com sucesso');

		return $response->withStatus(204);
	}

	public function show($request, $response, $args)
	{
		try {
			$user = $this->userRepository->find($args['id']);	
		} catch (ModelNotFoundException $e) {
			$this->errorLogger->error('Usuário de id ' . $args['id'] . ' não encontrado na base de dados');

			return $response->withJson('Usuário não encontrado', 404);
		}

		$this->logger->info('Usuário de id '. $user->id .' carregado com sucesso', [
			'user' => $user
		]);

		return $response->withJson($user, 200);
	}
}