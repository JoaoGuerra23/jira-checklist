<?php

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use App\Domain\Auth\AuthHelper;
use App\Domain\Validation\Validator;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use App\Infrastructure\Persistence\Repositories\UserAuthRepository;

class AuthRegisterAction extends Action
{
    private $userAuthRepository;
    /**
     * @var AuthHelper
     */
    private $authHelper;

    public function __construct(LoggerInterface $logger, UserAuthRepository $userAuthRepository, AuthHelper $authHelper)
    {
        parent::__construct($logger);
        $this->userAuthRepository = $userAuthRepository;
        $this->authHelper = $authHelper;
    }

    /**
     *
     * @OA\Post(
     *     tags={"user"},
     *     path="/auth/register",
     *     operationId="Register",
     *     description="User Register",
     *     summary="User Register",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                 ),
     *                 example={"name": "name", "email": "example@gmail.com", "password": "example"}
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
     */
    protected function action(): Response
    {
        $name = Validator::getParam($this->request, 'name');
        $email = Validator::getParam($this->request, 'email');
        $password = Validator::getParam($this->request, 'password');
        $password = $this->authHelper->hash($password);

        $user = $this->userAuthRepository->createUser($name, $email, $password);

        $message = "Created User with Name = " . $user->getName() . " && Email = " . $user->getEmail();

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
