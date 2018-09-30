<?php

namespace App\Validation;

interface ValidationInterface
{
	public static function validate($request, $validator);
}