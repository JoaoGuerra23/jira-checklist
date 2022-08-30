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
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 example={"name": "Tab1"}
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
        $name = $this->request->getParsedBody()['name'];

        $tab = $this->tabRepository->createNewTab($name);

        $message = 'Tab ' . $tab->getName() . ' Created.';

        $this->logger->info($message);

        return $this->respondWithData($message, 201);
    }
}
