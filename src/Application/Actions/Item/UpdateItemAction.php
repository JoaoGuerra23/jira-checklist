<?php

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Domain\Entities\Item\ItemDTO;
use App\Infrastructure\Persistence\Repositories\ItemRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class UpdateItemAction extends Action
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
     * @OA\Patch(
     *   path="/items/{id}",
     *   tags={"item"},
     *   path="/items/{id}",
     *   operationId="editItem",
     *   summary="Edit Item",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Item ID",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *   ),
     *         @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 example={"name": "ItemName"}
     *             )
     *         )
     *     ),
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
        (int)$id = $this->resolveArg('id');

        $parsedBody = $this->request->getParsedBody();

        $itemDTO = new ItemDTO($id);

        if (empty($this->itemRepository->findItemById($id))) {
            return $this->respondWithNotFound($itemDTO->getId());
        }

        $this->itemRepository->updateItem($parsedBody, $itemDTO);

        $message = "Item with ID " . $id . " was updated";

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
