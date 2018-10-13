<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class AuthController extends Controller
{
	public function auth($request, $response)
	{
		$data = $request->getParsedBody();

		try {
			$user = $this->userRepository->findOneByField('email', $data['email']);

			if ($user->password != md5($data['password'])) {
				throw new Exception("Senha informada está incorreta.", 1);
			}

		} catch (ModelNotFoundException $e) {
			$email = isset($data['email']) ? $data['email'] : '[ñ informado]';

			$this->errorLogger->error("Usuário de email {$email} não encontrado na base de dados");

			return $this->response->withJson('Usuário não encontrado', 404)
							->withHeader('Content-type', 'application/json');
		} catch (Exception $e) {
			$this->errorLogger->error("Usuário informou uma senha que não bate com a cadastrada");

			return $this->response->withJson($e->getMessage(), 404)
							->withHeader('Content-type', 'application/json');
		}

		$token = JWT::encode(['id' => $user->id, 'email' => $user->email], $this->settings['jwt']['secret'], "HS256");

		$this->logger->info("Token gerado com sucesso");

		return $response->withJson($token, 201)
					->withHeader('Content-type', 'application/json');
	}
}