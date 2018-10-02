<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\ResourceRouter\Resource;

// Routes
$app->post('/auth', 'App\Controllers\AuthController:auth');

Resource::createBaseRoute('users', $app);

Resource::createBaseRoute('clients', $app);

$app->get('/clients/{id}/saldo', 'App\Controllers\ClientsController:saldo');

$app->get('/clients/{id}/extrato', 'App\Controllers\ClientsController:extrato');

$app->get('/clients/{id}/creditos', 'App\Controllers\ClientsController:creditos');

$app->post('/recarregar/{id}', 'App\Controllers\CreditsController:recarregar');

$app->post('/remover/{id}', 'App\Controllers\CreditsController:remover');

$app->post('/estornar/{id}', 'App\Controllers\CreditsController:estornar');


