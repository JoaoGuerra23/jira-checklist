<?php

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Domain\Entities\Tab\TabDTO;
use App\Infrastructure\Persistence\Repositories\TabRepository;
use OpenApi\Annotations as OA;
use phpDocumentor\Reflection\Types\This;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class UpdateTabAction extends Action
{

    /**
     * @var TabRepository
     */
    private $tabRepository;

    public function __construct(LoggerInterface $logger, TabRepository $tabAuthRepository)
    {
        parent::__construct($logger);
        $this->tabRepository = $tabAuthRepository;
    }

    /**
     * @OA\Patch(
     *   path="/tabs/{name}",
     *   tags={"tab"},
     *   path="/tabs/{name}",
     *   operationId="editTab",
     *   summary="Edit Tab Name",
     *   @OA\Parameter(
     *          name="name",
     *          in="path",
     *          required=true,
     *          description="Tab name",
     *          @OA\Schema(
     *              type="string"
     *          )
     *   ),
     *
     *         @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 example={"name": "Tab-1"}
     *             )
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Tab")
     *   ),
     *     security={{"bearerAuth":{}}}
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $currentId = $this->resolveArg('id');
        $newId = $this->request->getParsedBody()['id'];

        $tabDTO = new TabDTO($currentId);

        if (empty($this->tabRepository->findTabById($currentId))) {
            return $this->respondWithNotFound($tabDTO->getId());
        }

        if ($currentId === $newId) {
            return $this->respondWithSameResources();
        }

        $this->tabRepository->updateTab($newId, $tabDTO);

        $message = "Tab name " . $currentId . " updated to " . $newId;

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
