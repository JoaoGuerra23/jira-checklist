<?php

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Domain\Item\ItemDTO;
use App\Infrastructure\Persistence\Repositories\ItemRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

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
     *   path="/items/{name}",
     *   operationId="deleteItem",
     *   summary="Delete Item by Name",
     *   @OA\Parameter(
     *          name="name",
     *          in="path",
     *          required=true,
     *          description="Item Name",
     *          @OA\Schema(
     *              type="string"
     *          )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Item")
     *   )
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $itemId = $this->resolveArg('id');

        $itemDTO = new ItemDTO($itemId);

        if (empty($this->itemRepository->findItemById($itemDTO))) {
            return $this->respondWithNotFound($itemId);
        }

        $this->itemRepository->deleteItemById($itemDTO);

        $message = "Item " . $itemId . " Deleted.";

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
