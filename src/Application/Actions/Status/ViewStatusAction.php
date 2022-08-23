<?php

namespace App\Application\Actions\Status;

use App\Application\Actions\Action;
use App\Domain\DTOs\StatusDTO;
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
     *   path="/status/{name}",
     *   operationId="getStatus",
     *   summary="Get Status by Name",
     *   @OA\Parameter(
     *          name="name",
     *          in="path",
     *          required=true,
     *          description="Status name",
     *          @OA\Schema(
     *              type="string"
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
        $statusName = $this->args['name'];

        $statusDTO = new StatusDTO($statusName);

        $status = $this->statusRepository->findStatusByName($statusDTO);

        if(empty($status)){
            return $this->respondWithNotFound($statusName);
        }

        $this->logger->info('Status ' . $statusName . ' was viewed');

        return $this->respondWithData($status);
    }
}
