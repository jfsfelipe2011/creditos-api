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
			return $this->response->withJson(['erro' => true, 'message' => 'Usuário não encontrado.'], 404)
							->withHeader('Content-type', 'application/json');
		} catch (Exception $e) {
			return $this->response->withJson(['erro' => true, 'message' => $e->getMessage()], 404)
							->withHeader('Content-type', 'application/json');
		}

		$token = JWT::encode(['id' => $user->id, 'email' => $user->email], $this->settings['jwt']['secret'], "HS256");

		return $response->withJson(['token' => $token], 201)
					->withHeader('Content-type', 'application/json');
	}
}