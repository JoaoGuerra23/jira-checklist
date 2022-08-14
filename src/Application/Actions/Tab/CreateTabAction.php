<?php

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\TabRepository;
use OpenApi\Annotations as OA;
use phpDocumentor\Reflection\Types\This;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateTabAction extends Action
{

    private $tabRepository;

    public function __construct(LoggerInterface $logger, TabRepository $tabRepository)
    {
        parent::__construct($logger);
        $this->tabRepository = $tabRepository;
    }

    /**
     * @OA\Post(
     *     tags={"tab"},
     *     path="/tabs",
     *     operationId="createTab",
     *     description="Create new Tab",
     *     summary="Create a new Tab",
     *      @OA\RequestBody(
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
     *     @OA\Response(
     *      response="200",
     *      description="OK",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Tab")
     *      )
     *     )
     * )
     */
    protected function action(): Response
    {
        $tab = $this->tabRepository->createNewTab($this->request, $this->response);

        $this->logger->info('Tab Created');

        return $this->respondWithData($tab, 201);
    }
}
