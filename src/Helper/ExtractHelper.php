<?php

namespace App\Helper;

use App\Models\Extract;
use Illuminate\Database\QueryException;
use Exception;

class ExtractHelper
{
    public static function save($dados, $type, $client)
    {
        try {
            $dados['type'] = $type;
            $dados['date'] = new \DateTime();
            $dados['operation'] = $type == 'remocao' ? 'debito' : 'credito';
            $dados['balance'] = $client->getSaldo();
            $dados['client_id'] = $client->id;

            Extract::create($dados);

        } catch (QueryException $e) {
            throw new Exception($e->getMessage());
        }
    }
}