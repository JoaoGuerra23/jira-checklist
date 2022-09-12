<?php

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\UserAuthRepository;
use App\Validation\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class AuthLoginAction extends Action
{
    private $userRepository;

    public function __construct(LoggerInterface $logger, UserAuthRepository $userAuthRepository)
    {
        parent::__construct($logger);
        $this->userRepository = $userAuthRepository;
    }

    protected function action(): Response
    {
        $email = Validator::getParam($this->request, 'email');
        $password = Validator::getParam($this->request, 'password');

        $verifyAccount = $this->userRepository->verifyUser($email, $password);

        if ($verifyAccount === false) {
            $responseMessage ="Invalid username or password";

            return $this->respondWithData($responseMessage, 400);
        }

        $token = GenerateTokenAction::generateToken($email);

        return $this->respondWithData($token);
    }
}
