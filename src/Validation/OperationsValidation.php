<?php

namespace App\Validation;

use Respect\Validation\Validator as Validation;

class OperationsValidation implements ValidationInterface
{
	public static function validate($request, $validator)
	{
		$validator = $validator->validate($request, [
			'credits' => [
				'rules' 	=> Validation::notEmpty()->numeric()->noWhitespace(),
				'messages'  => [
					'notEmpty'     => 'Créditos é obrigatório',
					'numeric'      => 'Créditos tem que ser um número',
					'noWhitespace' => 'Créditos não pode conter espaços',
				]
			],
			'description' => [
				'rules'     => Validation::notEmpty(),
				'messages'  => [
					'notEmpty'     => 'Descrição é obrigatório',
				]
			]
		]);

		return $validator;
	}
}