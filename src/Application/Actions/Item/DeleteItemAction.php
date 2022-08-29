<?php

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Domain\DTOs\ItemDTO;
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
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $itemName = $this->resolveArg('name');

        $itemDTO = new ItemDTO($itemName);

        if (empty($this->itemRepository->findItemByName($itemDTO))){
            return $this->respondWithNotFound($itemName);
        }

        $this->itemRepository->deleteItemByName($itemDTO);

        $message = "Item " . $itemName . " Deleted.";

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
