<?php

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use App\Validation\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use App\Infrastructure\Persistence\Repositories\UserAuthRepository;
use Respect\Validation\Validator as v;

class AuthRegisterAction extends Action
{
    private $userAuthRepository;

    public function __construct(LoggerInterface $logger, UserAuthRepository $userAuthRepository)
    {
        parent::__construct($logger);
        $this->userAuthRepository = $userAuthRepository;
    }

    protected function action(): Response
    {
        $name = Validator::getParam($this->request,'name');
        $email = Validator::getParam($this->request,'email');
        $password = Validator::getParam($this->request,'password');
        $hashedPassword = $this->hashPassword($password);

        $user = $this->userAuthRepository->createUser($name, $email, $hashedPassword);

        $message = "Create User with Name = " .
            $user->getName() . " && Email = " .
            $user->getEmail() . " && PW = " .
            $user->getPassword();

        $this->logger->info($message);

        return $this->respondWithData($message);
    }

    public function hashPassword($password)
    {
        return password_hash($password,PASSWORD_DEFAULT);
    }
}