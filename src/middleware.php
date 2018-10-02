<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(new \Tuupola\Middleware\HttpBasicAuthentication([

    "users" => [
        "admin@email.com" => 'senac2018'
    ],
	"path" => ["/users"],
    "realm" => "Protected",
    "secure" => false,
    "relaxed" => ["localhost", "http://creditos/#/"],
	"error" => function ($response, $arguments) {
        $data["status"] = "erro";
        $data["message"] = "Informe usuário e senha";
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));


$app->add(new \Tuupola\Middleware\JwtAuthentication([
    "path" => ["/clients", "/recarregar", "/remover", "/estornar"], /* or ["/api", "/admin"] */
    "realm" => "Protected",
    "secure" => false,
    "relaxed" => ["localhost", "http://creditos/#/"],
    "attribute" => "decoded_token_data",
    "secret" => "3Rz7znk032mmtL7oLcP5YvbzG7jwqk0Wjr4U9W20",
    "algorithm" => ["HS256"],
    "error" => function ($response, $arguments) {
        $data["status"] = "erro";
        $data["message"] = "Token não encontrado.";
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));