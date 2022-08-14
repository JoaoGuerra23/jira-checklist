<?php

namespace App\Application\Actions\Status;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\StatusRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class UpdateStatusAction extends Action
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
     * @OA\Patch(
     *   path="/status/{id}",
     *   tags={"status"},
     *   path="/status/{id}",
     *   operationId="editStatus",
     *   summary="Edit Status Name",
     *         @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 example={"id": 1, "code": "TO DO"}
     *             )
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Edited Status",
     *     @OA\JsonContent(ref="#/components/schemas/Status")
     *   )
     * )
     */
    protected function action(): Response
    {
        $status = $this->statusRepository->updateStatusName($this->request, $this->response);

        $this->logger->info("Status Name Edited");

        return $this->respondWithData($status);
    }
}
