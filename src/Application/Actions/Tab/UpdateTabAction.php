<?php

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\TabRepository;
use OpenApi\Annotations as OA;
use phpDocumentor\Reflection\Types\This;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class UpdateTabAction extends Action
{
    private $tabRepository;

    public function __construct(LoggerInterface $logger, TabRepository $tabRepository)
    {
        parent::__construct($logger);
        $this->tabRepository = $tabRepository;
    }

    /**
     * @OA\Patch(
     *   path="/tabs/{id}",
     *   tags={"tab"},
     *   path="/tabs/{id}",
     *   operationId="editTab",
     *   summary="Edit Tab Name",
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
     *                 example={"id": 1, "code": "Tab1"}
     *             )
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Edited Ticket",
     *     @OA\JsonContent(ref="#/components/schemas/Tab")
     *   )
     * )
     */
    protected function action(): Response
    {
        $tabs = $this->tabRepository->updateTabName($this->request, $this->response);

        $this->logger->info('Tab Edited');

        return $this->respondWithData($tabs);
    }
}
