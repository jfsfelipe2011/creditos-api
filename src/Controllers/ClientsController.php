<?php

namespace App\Controllers;

class ClientsController extends Controller
{
	public function index($request, $response)
	{	
		$data = $request->getAttribute('decoded_token_data');

		$clients = $this->clientRepository->findByField('user_id', $data['id']);

		return $response->withJson($clients, 200)
					->withHeader('Content-type', 'application/json');
	}
}