<?php

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\ItemRepository;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ViewItemAction extends Action
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
     * @OA\Get(
     *   tags={"item"},
     *   path="/items/{id}",
     *   operationId="getItem",
     *   summary="Get Item by ID",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Item id",
     *          @OA\Schema(
     *              type="integer"
     *   )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="A single Item",
     *     @OA\JsonContent(ref="#/components/schemas/Item")
     *   )
     * )
     */
    protected function action(): Response
    {
        $item = $this->itemRepository->findItemById($this->args);

        $this->logger->info('Get single Item');

        return $this->respondWithData($item);
    }
}
