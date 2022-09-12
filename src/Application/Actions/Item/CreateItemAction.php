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

    public function __construct(LoggerInterface $logger, ItemRepository $itemAuthRepository)
    {
        parent::__construct($logger);
        $this->itemRepository = $itemAuthRepository;
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
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="statusId",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="ownerId",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="ticketId",
     *                     type="int"
     *                 ),@OA\Property(
     *                     property="sectionId",
     *                     type="int"
     *                 ),
     *                 example={"name": "ItemName", "ownerId" : 1,"statusId": 1, "ticketId" : 1, "sectionId": 1}
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
     *
     */
    protected function action(): Response
    {
        $parsedBody = $this->request->getParsedBody();

        $item = $this->itemRepository->createNewItem($parsedBody);

        $message = "Item " . $item->getName() . " Created.";

        $this->logger->info($message);

        return $this->respondWithData($message, 201);
    }
}
