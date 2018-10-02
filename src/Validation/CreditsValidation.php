<?php

namespace App\Validation;

use Respect\Validation\Validator as Validation;

class CreditsValidation implements ValidationInterface
{
	public static function validate($request, $validator)
	{
		$validator = $validator->validate($request, [
			'value' => [
				'rules' 	=> Validation::notEmpty()->numeric()->noWhitespace(),
				'messages'  => [
					'notEmpty'  => 'Valor é obrigatório',
					'numeric' => 'Valor tem que ser um número',
					'noWhitespace' => 'Valor não pode conter espaços',
				]
			],
			'validity' => [
				'rules'     => Validation::notEmpty()->noWhitespace()->date('Y-m-d'),
				'messages'  => [
					'notEmpty'     => 'Validade é obrigatória',
					'noWhitespace' => 'Validade não pode conter espaços',
					'date'         => 'Validade tem que ter o formato yyyy-mm-dd'
					
				]
			],
			'description' => [
				'rules'     => Validation::notEmpty(),
				'messages'  => [
					'notEmpty'     => 'Descrição é obrigatória',
				]
			],
		]);

		return $validator;
	}
}