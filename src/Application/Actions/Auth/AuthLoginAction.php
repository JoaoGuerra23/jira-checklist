<?php

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use App\Domain\Auth\AuthHelper;
use App\Infrastructure\Persistence\Repositories\UserAuthRepository;
use App\Domain\Validation\Validator;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class AuthLoginAction extends Action
{
    private $userRepository;

    /**
     * @var AuthHelper
     */
    private $authHelper;

    public function __construct(LoggerInterface $logger, UserAuthRepository $userAuthRepository, AuthHelper $authHelper)
    {
        parent::__construct($logger);
        $this->userRepository = $userAuthRepository;
        $this->authHelper = $authHelper;
    }


    /**
     *
     * @OA\Post(
     *     tags={"user"},
     *     path="/auth/login",
     *     operationId="Login",
     *     description="Login User",
     *     summary="Login User",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                 ),
     *                 example={"email": "example@gmail.com", "password": "example"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="OK",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/User")
     *      )
     *     )
     * )
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    protected function action(): Response
    {
        $email = Validator::getParam($this->request, 'email');
        $password = Validator::getParam($this->request, 'password');
        $password = $this->authHelper->hash($password);

        $this->userRepository->getUserByEmailAndPassword($email, $password);

        $token = GenerateTokenAction::generateToken($email);

        return $this->respondWithData($token);
    }
}
