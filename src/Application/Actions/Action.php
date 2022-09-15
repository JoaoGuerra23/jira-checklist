<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Domain\Exceptions\DomainRecordNotFoundException;
use App\Domain\Exceptions\BadRequestException;
use App\Domain\Exceptions\NotAllowedException;
use App\Domain\Exceptions\NotFoundException;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;

/**
 * @OA\Server(url="https://sandbox.exads.rocks")
 * @OA\Info(title="Jira-Checklist", version="0.1")
 * @OA\SecurityScheme(
 *     type="http",
 *     description="use /auth/login to get the JWT Token",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */
abstract class Action
{
    protected $logger;

    protected $request;

    protected $response;

    protected $args;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     * @throws HttpMethodNotAllowedException
     * @throws DomainRecordNotFoundException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return $this->action();
        } catch (BadRequestException $e) {
            throw new HttpBadRequestException($this->request, $e->getMessage());
        } catch (NotAllowedException $e) {
            throw new HttpMethodNotAllowedException($this->request, $e->getMessage());
        } catch (NotFoundException|DomainRecordNotFoundException $e) {
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }

    /**
     * @throws DomainRecordNotFoundException
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @return array|object
     */
    protected function getFormData()
    {
        return $this->request->getParsedBody();
    }

    /**
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param array|object|null $data
     */
    protected function respondWithData($data = null, int $statusCode = 200): Response
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload);
    }

    protected function respondWithNotFound($id): Response
    {
        return $this->respondWithData("Resource `{$id}` Not Found", 404);
    }

    protected function respondWithSameResources(): Response
    {
        return $this->respondWithData("Http body supposed to be different from the attribute", 400);
    }

    protected function respond(ActionPayload $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus($payload->getStatusCode());
    }
}
