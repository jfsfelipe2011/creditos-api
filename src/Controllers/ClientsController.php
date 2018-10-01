<?php

namespace App\Controllers;

use App\Validation\ClientsValidation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class ClientsController extends Controller
{
	public function index($request, $response)
	{	
		$auth = $request->getAttribute('decoded_token_data');

		$clients = $this->clientRepository->findByField('user_id', $auth['id']);

		$this->logger->info('Clientes do usuário ' . $auth['id'] . ' listados com sucesso', [
			'quantidade' => count($clients)
		]);

		return $response->withJson($clients, 200)
					->withHeader('Content-type', 'application/json');
	}

	public function store($request, $response)
	{
		$data = $request->getParsedBody();
		$auth = $request->getAttribute('decoded_token_data');
		$data['user_id'] = $auth['id'];

		$validation = ClientsValidation::validate($request, $this->validator);

		if (!$validation->isValid()) {
			$this->errorLogger->error('Erro na validação de Cliente', [
				'Erros' => $validation->getErrors()
			]);

			return $response->withJson($validation->getErrors(), 400);
		}

		try {
			$insert = $this->clientRepository->create($data);
		} catch (QueryException $e) {
			$this->errorLogger->error('Erro ao inserir o cliente', [
				'erros' => $e->getMessage()
			]);

			return $response->withJson('Erro ao criar cliente', 400);
		} 

		$this->logger->info('Cliente criado com sucesso', [
			'data' => $insert
		]);

		return $response->withJson($insert, 201);
	}

	public function update($request, $response, $args)
	{
		$data = $request->getParsedBody();
		$auth = $request->getAttribute('decoded_token_data');
		$data['user_id'] = $auth['id'];

		$validation = ClientsValidation::validate($request, $this->validator);

		if (!$validation->isValid()) {
			$this->errorLogger->error('Erro na validação de Cliente', [
				'Erros' => $validation->getErrors()
			]);

			return $response->withJson($validation->getErrors(), 400);
		}

		try {
			$update = $this->clientRepository->update([
				'id'      => $args['id'],
				'user_id' => $auth['id']
			], $data);	
		} catch (QueryException $e) {
			$this->errorLogger->error('Erro ao atualizar o Cliente', [
				'erros' => $e->getMessage()
			]);

			return $response->withJson('Erro ao editar Cliente', 400);
		} catch (ModelNotFoundException $e) {
			$this->errorLogger->error('Cliente de id ' . $args['id'] . ' não encontrado na base de dados');

			return $response->withJson('Cliente não encontrado', 404);
		}

		$this->logger->info('Cliente atualizado com sucesso', [
			'data' => $update
		]);

		return $response->withJson($update, 200);
	}

	public function delete($request, $response, $args)
	{
		$auth = $request->getAttribute('decoded_token_data');

		try {
			$this->clientRepository->delete([
				'id' 	  => $args['id'],
				'user_id' => $auth['id']
			]);	
		} catch (QueryException $e) {
			$this->errorLogger->error('Erro ao excluir o Cliente', [
				'erros' => $e->getMessage()
			]);

			return $response->withJson('Erro ao excluir Cliente', 400);
		} catch (ModelNotFoundException $e) {
			$this->errorLogger->error('Cliente de id ' . $args['id'] . ' não encontrado na base de dados');

			return $response->withJson('Cliente não encontrado', 404);
		}

		$this->logger->info('Cliente de id '. $args['id'] .' excluido com sucesso');

		return $response->withStatus(204);
	}

	public function show($request, $response, $args)
	{
		$auth = $request->getAttribute('decoded_token_data');

		try {
			$client = $this->clientRepository->findOneByFields([
				'id' 		=> $args['id'],
				'user_id'   => $auth['id']
			]);	
		} catch (ModelNotFoundException $e) {
			$this->errorLogger->error('Cliente de id ' . $args['id'] . ' não encontrado na base de dados');

			return $response->withJson('Cliente não encontrado', 404);
		}

		$this->logger->info('Cliente de id '. $client->id .' carregado com sucesso', [
			'client' => $client
		]);

		return $response->withJson($client, 200);
	}
}