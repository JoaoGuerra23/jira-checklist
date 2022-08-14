<?php

namespace App\Application\Actions\Status;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\StatusRepository;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use function Symfony\Component\String\s;

class ViewStatusAction extends Action
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
     * @OA\Get(
     *   tags={"status"},
     *   path="/status/{id}",
     *   operationId="getStatus",
     *   summary="Get Status by ID",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Status id",
     *          @OA\Schema(
     *              type="integer"
     *   )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Status")
     *   )
     * )
     */
    protected function action(): Response
    {
        $status = $this->statusRepository->findStatusById($this->args);

        $this->logger->info('Get Status by ID');

        return $this->respondWithData($status);
    }
}
