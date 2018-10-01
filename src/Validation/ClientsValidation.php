<?php

namespace App\Validation;

use Respect\Validation\Validator as Validation;

class ClientsValidation implements ValidationInterface
{
	public static function validate($request, $validator)
	{
		$messages = [
			'name' => [
				'rules' 	=> Validation::notEmpty(),
				'messages'  => [
					'notEmpty' => 'Nome é obrigatório'
				]
			],
			'email' => [
				'rules'     => Validation::notEmpty()->noWhitespace()->email(),
				'messages'  => [
					'notEmpty'     => 'E-mail é obrigatório',
					'noWhitespace' => 'E-mail não pode conter espaços',
					'email'		   => 'Informe um e-mail válido'
				]
			],
			'fone' => [
				'rules'     => Validation::notEmpty(),
				'messages'  => [
					'notEmpty' => 'Telefone é obrigatório',
				]
			]
		];

		$document = $request->getParam('document');

		if (strlen($document) < 12) {
			$messages['document'] = [
				'rules'     => Validation::notEmpty()->cpf(),
				'messages'  => [
					'notEmpty' => 'Documento é obrigatório',
					'cpf'      => 'Informe um cpf válido'
				]
			];
		} else {
			$messages['document'] = [
				'rules'     => Validation::notEmpty()->cnpj(),
				'messages'  => [
					'notEmpty' => 'Documento é obrigatório',
					'cnpj'     => 'Informe um cnpj válido'
				]
			];
		}


		$validator = $validator->validate($request, $messages);

		return $validator;
	}
}