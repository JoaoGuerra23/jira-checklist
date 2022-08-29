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

class DeleteTabAction extends Action
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
     * @OA\Delete(
     *   tags={"tab"},
     *   path="/tabs/{name}",
     *   operationId="deleteTab",
     *   summary="Delete Tab by Name",
     *   @OA\Parameter(
     *          name="name",
     *          in="path",
     *          required=true,
     *          description="Tab name",
     *          @OA\Schema(
     *              type="string"
     *          )
     *   ),
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
        $name = $this->resolveArg('name');

        $tabDTO = new TabDTO($name);

        if (empty($this->tabRepository->findTabByName($tabDTO))){
            return $this->respondWithNotFound($name);
        }

        $this->tabRepository->deleteTabByName($tabDTO);

        $message = "Tab " . $name . " Deleted.";

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
