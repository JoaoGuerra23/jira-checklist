<?php

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Domain\Entities\Tab\TabDTO;
use App\Infrastructure\Persistence\Repositories\TabRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class DeleteTabAction extends Action
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
     *   ),
     *     security={{"bearerAuth":{}}}
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $id = $this->resolveArg('id');

        if (empty($this->tabRepository->findTabById($id))) {
            return $this->respondWithNotFound($id);
        }

        $this->tabRepository->deleteTabById($id);

        $message = "Tab " . $id . " Deleted.";

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
