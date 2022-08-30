<?php

namespace App\Application\Actions\Status;

use App\Application\Actions\Action;
use App\Domain\DTOs\StatusDTO;
use App\Infrastructure\Persistence\Repositories\StatusRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

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
     *   path="/status/{name}",
     *   tags={"status"},
     *   path="/status/{name}",
     *   operationId="editStatus",
     *   summary="Edit Status Name",
     *   @OA\Parameter(
     *          name="name",
     *          in="path",
     *          required=true,
     *          description="Status Name",
     *          @OA\Schema(
     *              type="string"
     *          )
     *   ),
     *         @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 example={"code": "COMPLETED"}
     *             )
     *         )
     *     ),
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
        $currentName = $this->resolveArg('name');

        $statusDTO = new StatusDTO($currentName);

        if (empty($this->statusRepository->findStatusByName($statusDTO))) {
            return $this->respondWithNotFound($statusDTO->getName());
        }

        $status = $this->statusRepository->updateStatusName($this->request, $statusDTO);

        $updatedName = $status->jsonSerialize()['name'];

        $message = "Status " . $currentName . " updated to " . $updatedName;

        if ($currentName == $updatedName) {
            return $this->respondWithSameResources();
        }

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
