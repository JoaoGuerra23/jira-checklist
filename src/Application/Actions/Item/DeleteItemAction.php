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

    public function __construct(LoggerInterface $logger, ItemRepository $itemAuthRepository)
    {
        parent::__construct($logger);
        $this->itemRepository = $itemAuthRepository;
    }

    /**
     * @OA\Delete(
     *   tags={"item"},
     *   path="/items/{id}",
     *   operationId="deleteItem",
     *   summary="Delete Item by Id",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Item Id",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Item")
     *   ),
     *     security={{"bearerAuth":{}}}
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $id = $this->resolveArg('id');

        if (empty($this->itemRepository->findItemById($id))) {
            return $this->respondWithNotFound($id);
        }

        $this->itemRepository->deleteItemById($id);

        $message = "Item " . $id . " Deleted.";

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
