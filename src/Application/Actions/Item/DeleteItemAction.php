<?php

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\ItemRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class DeleteItemAction extends Action
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
     * @OA\Delete(
     *   tags={"item"},
     *   path="/items/{id}",
     *   operationId="deleteItem",
     *   summary="Delete Item by ID",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Item id",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Item")
     *   )
     * )
     */
    protected function action(): Response
    {

        $item = $this->itemRepository->deleteItemById($this->response, $this->args);

        $this->logger->info("Ticket Deleted");

        return $this->respondWithData($item);
    }

}