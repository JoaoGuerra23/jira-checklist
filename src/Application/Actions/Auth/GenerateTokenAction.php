<?php

namespace App\Application\Actions\Auth;

use App\Application\Interfaces\SecretKeyInterface;
use Firebase\JWT\JWT;

class GenerateTokenAction implements SecretKeyInterface
{
    public static function generateToken($email): string
    {
        $now = time();
        $future = strtotime('+1 hour', $now);
        $secret = SecretKeyInterface::JWT_SECRET_KEY;

        $payload = [
            "jti"=>$email,
            "iat"=>$now,
            "exp"=>$future
        ];

        return JWT::encode($payload, $secret, "HS256");
    }

}