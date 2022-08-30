<?php

namespace App\Application\Actions\Status;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\StatusRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateStatusAction extends Action
{

    private $statusRepository;

    public function __construct(LoggerInterface $logger, StatusRepository $statusRepository)
    {
        parent::__construct($logger);
        $this->statusRepository = $statusRepository;
    }


    /**
     * @OA\Post(
     *     tags={"status"},
     *     path="/status",
     *     operationId="createStatus",
     *     description="Create new Status",
     *     summary="Create a new Status",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 example={"name": "COMPLETED"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="OK",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Status")
     *      )
     *     )
     * )
     */
    protected function action(): Response
    {
        $name = $this->request->getParsedBody()['name'];

        $status = $this->statusRepository->createNewStatus($name);

        $message = 'Status ' . $status->getName() . ' Created';

        $this->logger->info($message);

        return $this->respondWithData($message, 201);
    }
}
