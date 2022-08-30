<?php

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Domain\DTOs\ItemDTO;
use App\Infrastructure\Persistence\Repositories\ItemRepository;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use phpDocumentor\Reflection\Types\This;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

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
     *   path="/items/{name}",
     *   tags={"item"},
     *   path="/items/{name}",
     *   operationId="editItem",
     *   summary="Edit Item Name",
     *   @OA\Parameter(
     *          name="name",
     *          in="path",
     *          required=true,
     *          description="Item Name",
     *          @OA\Schema(
     *              type="string"
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
     *   )
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $currentName = $this->resolveArg('name');
        $newName = $this->request->getParsedBody()['name'];

        $itemDTO = new ItemDTO($currentName);

        if (empty($this->itemRepository->findItemByName($itemDTO))) {
            return $this->respondWithNotFound($itemDTO->getName());
        }

        if ($currentName === $newName) {
            return $this->respondWithSameResources();
        }

        $this->itemRepository->updateItemName($newName, $itemDTO);

        $message = "Item Name " . $currentName . " updated to " . $newName;

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
