<?php

namespace App\Validation;

use Respect\Validation\Validator as Validation;

class UsersValidation implements ValidationInterface
{
	public static function validate($request, $validator)
	{
		$validator = $validator->validate($request, [
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
			'password' => [
				'rules'     => Validation::notEmpty()->noWhitespace()->length(5, 20),
				'messages'  => [
					'notEmpty'     => 'Senha é obrigatória',
					'noWhitespace' => 'Senha não pode conter espaços',
					'length'	   => 'Senha deve conter entre 5 a 20 caracteres',
				]
			]
		]);

		return $validator;
	}
}