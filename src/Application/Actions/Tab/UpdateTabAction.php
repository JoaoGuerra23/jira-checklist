<?php

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Domain\DTOs\TabDTO;
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

    public function __construct(LoggerInterface $logger, TabRepository $tabRepository)
    {
        parent::__construct($logger);
        $this->tabRepository = $tabRepository;
    }

    /**
     * @OA\Patch(
     *   path="/tabs/{name}",
     *   tags={"tab"},
     *   path="/tabs/{name}",
     *   operationId="editTab",
     *   summary="Edit Tab Name",
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
     *   )
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $currentName = $this->resolveArg('name');

        $tabDTO = new TabDTO($currentName);

        if (empty($this->tabRepository->findTabByName($tabDTO))) {
            return $this->respondWithNotFound($tabDTO->getName());
        }

        $tab = $this->tabRepository->updateTabName($this->request, $tabDTO);

        $updatedName = $tab->jsonSerialize()['name'];

        $message = "Tab name " . $currentName . " updated to " . $updatedName;

        if ($currentName == $updatedName) {
            return $this->respondWithSameResources();
        }

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
