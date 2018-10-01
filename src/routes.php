<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\ResourceRouter\Resource;

// Routes
$app->post('/auth', 'App\Controllers\AuthController:auth');

Resource::createBaseRoute('users', $app);

Resource::createBaseRoute('clients', $app);


