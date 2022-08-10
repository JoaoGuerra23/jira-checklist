<?php

namespace App\Application\Actions\Status;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\StatusRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class DeleteStatusAction extends Action
{

    /**
     * @var StatusRepository
     */
    private $statusRepository;

    public function __construct(LoggerInterface $logger, StatusRepository $statusRepository)
    {
        parent::__construct($logger);
        $this->statusRepository = $statusRepository;
    }

    /**
     * @OA\Delete(
     *   tags={"status"},
     *   path="/status/{id}",
     *   operationId="deleteStatus",
     *   summary="Delete Status by ID",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Status id",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Status")
     *   )
     * )
     */
    protected function action(): Response
    {

        $status = $this->statusRepository->deleteStatusById($this->response, $this->args);

        $this->logger->info("Status Deleted");

        return $this->respondWithData($status);
    }

}