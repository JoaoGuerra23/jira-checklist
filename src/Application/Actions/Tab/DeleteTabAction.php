<?php

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\TabRepository;
use OpenApi\Annotations as OA;
use phpDocumentor\Reflection\Types\This;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class DeleteTabAction extends Action
{
    private $tabRepository;

    public function __construct(LoggerInterface $logger, TabRepository $tabRepository)
    {
        parent::__construct($logger);
        $this->tabRepository = $tabRepository;
    }

    /**
     * @OA\Delete(
     *   tags={"tab"},
     *   path="/tabs/{id}",
     *   operationId="deleteTab",
     *   summary="Delete Tab by ID",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Tab id",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Tab")
     *   )
     * )
     */
    protected function action(): Response
    {
        $tabs = $this->tabRepository->deleteTabById($this->response, $this->args);

        $this->logger->info('Tab Deleted');

        return $this->respondWithData($tabs);
    }
}
