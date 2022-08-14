<?php

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\ItemRepository;
use OpenApi\Annotations as OA;
use phpDocumentor\Reflection\Types\This;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateItemAction extends Action
{

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function __construct(LoggerInterface $logger, ItemRepository $itemRepository)
    {
        parent::__construct($logger);
        $this->itemRepository = $itemRepository;
    }

    /**
     * @OA\Post(
     *     tags={"item"},
     *     path="/items",
     *     operationId="createItem",
     *     description="Create new Item",
     *     summary="Create a new Item",
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
     *                 example={"id": 1, "code": "Item name here"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="OK",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Item")
     *      )
     *     )
     * )
     */
    protected function action(): Response
    {
        $item = $this->itemRepository->createNewItem($this->request, $this->response);

        $this->logger->info('Item Created');

        return $this->respondWithData($item, 201);
    }
}
