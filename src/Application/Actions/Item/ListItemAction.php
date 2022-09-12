<?php

namespace App\Application\Actions\Item;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\ItemRepository;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ListItemAction extends Action
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
     *     tags={"item"},
     *     path="/items",
     *     operationId="getItems",
     *     summary="List all Items",
     *     @OA\Response(
     *      response="200",
     *      description="List all Items",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Item")
     *      )
     *     )
     * )
     */
    protected function action(): Response
    {
        $items = $this->itemRepository->findAllItems();

        $this->logger->info("Item list was viewed");

        return $this->respondWithData($items);
    }
}
