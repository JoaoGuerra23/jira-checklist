<?php

namespace App\Application\Actions\Status;

use App\Application\Actions\Action;
use App\Domain\Status\StatusDTO;
use App\Infrastructure\Persistence\Repositories\StatusRepository;
use OpenApi\Annotations as OA;
use phpDocumentor\Reflection\Types\This;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class DeleteStatusAction extends Action
{

    /**
     * @var StatusRepository
     */
    private $statusRepository;

    public function __construct(LoggerInterface $logger, StatusRepository $statusAuthRepository)
    {
        parent::__construct($logger);
        $this->statusRepository = $statusAuthRepository;
    }

    /**
     * @OA\Delete(
     *   tags={"status"},
     *   path="/status/{name}",
     *   operationId="deleteStatus",
     *   summary="Delete Status by Name",
     *   @OA\Parameter(
     *          name="name",
     *          in="path",
     *          required=true,
     *          description="Status Name",
     *          @OA\Schema(
     *              type="string"
     *          )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Status")
     *   )
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $name = $this->resolveArg('name');

        $statusDTO = new StatusDTO($name);

        if (empty($this->statusRepository->findStatusByName($statusDTO))) {
            return $this->respondWithNotFound($name);
        }

        $this->statusRepository->deleteStatusByName($statusDTO);

        $message = "Status " . $name . " Deleted";

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
