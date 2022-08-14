<?php

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\ItemRepository;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class UpdateItemAction extends Action
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
     * @OA\Patch(
     *   path="/items/{id}",
     *   tags={"item"},
     *   path="/items/{id}",
     *   operationId="editItem",
     *   summary="Edit Item Name",
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
     *                 example={"id": 1, "code": "Item Name goes Here"}
     *             )
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Item")
     *   )
     * )
     */
    protected function action(): Response
    {
        $items = $this->itemRepository->updateItemSubject($this->request, $this->response);

        $this->logger->info("Item Name Edited");

        return $this->respondWithData($items);
    }
}
