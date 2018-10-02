<?php

namespace App\Helper;

use App\Models\Extract;
use Illuminate\Database\QueryException;
use Exception;

class OperationsHelper
{
    public static function removeCreditos($creditos, $cliente)
    {
        try {
            foreach ($cliente->credit as $credit) {
                $subtracao = $credit->balance - $creditos;

                if ($subtracao >= 0) {
                    $credit->balance = $subtracao;
                    $credit->save();
                    break;
                }

                $credit->balance = 0;
                $credit->save();

                $creditos = abs($subtracao);
            }
        } catch (QueryException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function estornaCreditos($creditos, $cliente)
    {
        try {
            $credits = $cliente->getCreditosAEstornar;

            foreach ($credits as $credit) {

                if ($credit->balance == $credit->value) {
                    continue;
                }

                $diferenca = $credit->value - $credit->balance;

                if ($diferenca < $creditos) {
                    $credit->balance = $credit->balance + $diferenca;
                    $credit->save();

                    $creditos = $creditos - $diferenca;
                } else {
                    $credit->balance = $credit->balance + $creditos;
                    $credit->save();

                    $creditos = 0;
                }

                if($creditos == 0) {
                    break;
                }

            }

            return $creditos;

        } catch (QueryException $e) {
            throw new Exception($e->getMessage());
        }
    }
}