<?php

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Domain\Entities\Item\ItemDTO;
use App\Infrastructure\Persistence\Repositories\ItemRepository;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class ViewItemAction extends Action
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
     * @OA\Get(
     *   tags={"item"},
     *   path="/items/{id}",
     *   operationId="getItem",
     *   summary="Get Item by ID",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Item ID",
     *          @OA\Schema(
     *              type="integer"
     *   )
     * ),
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
        $itemId = (int)$this->resolveArg('id');

        $itemDTO = new ItemDTO($itemId);

        $item = $this->itemRepository->findItemById($itemDTO);

        if (empty($item)) {
            return $this->respondWithNotFound($itemId);
        }

        $this->logger->info("Item " . $itemId . " was viewed.");

        return $this->respondWithData($item);
    }
}
