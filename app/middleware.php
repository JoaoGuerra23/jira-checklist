<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;
use App\Application\Interfaces\SecretKeyInterface as Secret;

return function (App $app) {

    $app->add(new Tuupola\Middleware\JwtAuthentication([
        "ignore"=>["/auth/register", "/auth/login", "/", "/users"],
        "secure" => false,
        "secret" => Secret::JWT_SECRET_KEY,
        "error" => function ($response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];

            $response->getBody()->write(
                json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            );

            return $response->withHeader("Content-Type", "application/json");
    }
    ]));

    $app->add(SessionMiddleware::class);
};
