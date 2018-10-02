<?php

namespace App\Controllers;

use App\Helper\ExtractHelper;
use App\Helper\OperationsHelper;
use App\Validation\CreditsValidation;
use App\Validation\OperationsValidation;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class CreditsController extends Controller
{
	public function recarregar($request, $response, $args)
	{
		$auth = $request->getAttribute('decoded_token_data');

		$validation = CreditsValidation::validate($request, $this->validator);

		if (!$validation->isValid()) {
			$this->errorLogger->error('Erro na validação de Créditos', [
				'Erros' => $validation->getErrors()
			]);

			return $response->withJson($validation->getErrors(), 400);
		}

		try {
			$client = $this->clientRepository->findOneByFields([
				'id' 		=> $args['id'],
				'user_id'   => $auth['id']
			]);

			$data = $request->getParsedBody();
			$data['balance'] = $data['value'];
			$data['client_id'] = $client->id;

			$insert = $this->creditRepository->create($data);

			$this->logger->info('Creditos recarregados com sucesso', [
				'data' => $insert
			]);

			unset($data['validity']);
            $data['credits'] = $data['value'];
            unset($data['value']);

            ExtractHelper::save($data, 'recarga', $client);

            $this->logger->info('Linha de extrato criada para o cliente ' . $args['id']);

            return $response->withJson($insert, 201);

		} catch (ModelNotFoundException $e) {
			$this->errorLogger->error('Cliente de id ' . $args['id'] . ' não encontrado na base de dados');

			return $response->withJson('Cliente não encontrado', 404);
		} catch (QueryException $e) {
			$this->errorLogger->error('Erro ao efetuar recarga para o cliente ' . $args['id'], [
				'erros' => $e->getMessage()
			]);

			return $response->withJson('Erro ao efetuar recarga', 400);
		} catch (Exception $e) {
			$this->errorLogger->error('Erro ao gravar extrato para o cliente ' . $args['id'], [
				'erros' => $e->getMessage()
			]);

			return $response->withJson('Erro ao efetuar recarga', 400);
		}
	}

	public function remover($request, $response, $args)
	{
		$auth = $request->getAttribute('decoded_token_data');

		$validation = OperationsValidation::validate($request, $this->validator);

		if (!$validation->isValid()) {
			$this->errorLogger->error('Erro na validação das Operações', [
				'Erros' => $validation->getErrors()
			]);

			return $response->withJson($validation->getErrors(), 400);
		}

		try {
			$client = $this->clientRepository->findOneByFields([
				'id' 		=> $args['id'],
				'user_id'   => $auth['id']
			]);

			$data = $request->getParsedBody();

			$excedente = $data['credits'] - $client->getSaldo();

			OperationsHelper::removeCreditos($data['credits'], $client);

			$this->logger->info('Removido ' . $data['credits'] . ' créditos do cliente de id ' . $args['id']);

			ExtractHelper::save($data, 'remocao', $client);

			$this->logger->info('Linha de extrato criada para o cliente ' . $args['id']);

			if ($excedente > 0) {
                $data['credits'] = $data['credits'] - $excedente;
            } else {
                $excedente = 0;
            }

            $result = [
                'removido'  => $data['credits'],
                'excedente' => $excedente
            ];

            return $response->withJson($result, 200);

		} catch (ModelNotFoundException $e) {
			$this->errorLogger->error('Cliente de id ' . $args['id'] . ' não encontrado na base de dados');

			return $response->withJson('Cliente não encontrado', 404);
		} catch (Exception $e) {
			$this->errorLogger->error('Erro ao remover créditos para o cliente ' . $args['id'], [
				'erros' => $e->getMessage()
			]);

			return $response->withJson('Erro ao remover creditos', 400);
		}
	}

	public function estornar($request, $response, $args)
	{
		$auth = $request->getAttribute('decoded_token_data');

		$validation = OperationsValidation::validate($request, $this->validator);

		if (!$validation->isValid()) {
			$this->errorLogger->error('Erro na validação das Operações', [
				'Erros' => $validation->getErrors()
			]);

			return $response->withJson($validation->getErrors(), 400);
		}

		try {
			$client = $this->clientRepository->findOneByFields([
				'id' 		=> $args['id'],
				'user_id'   => $auth['id']
			]);

			$data = $request->getParsedBody();

			$excedente = OperationsHelper::estornaCreditos($data['credits'], $client);

			$this->logger->info('Estornado ' . $data['credits'] . ' créditos do cliente de id ' . $args['id']);

			ExtractHelper::save($data, 'estorno', $client);

			$this->logger->info('Linha de extrato criada para o cliente ' . $args['id']);

			if ($excedente > 0) {
                $data['credits'] = $data['credits'] - $excedente;
            } else {
                $excedente = 0;
            }

            $result = [
                'estornado' => $data['credits'],
                'excedente' => $excedente
            ];

            return $response->withJson($result, 200);

		} catch (ModelNotFoundException $e) {
			$this->errorLogger->error('Cliente de id ' . $args['id'] . ' não encontrado na base de dados');

			return $response->withJson('Cliente não encontrado', 404);
		} catch (Exception $e) {
			$this->errorLogger->error('Erro ao estornar créditos para o cliente ' . $args['id'], [
				'erros' => $e->getMessage()
			]);

			return $response->withJson('Erro ao estornar creditos', 400);
		}
	}
}