<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\TabRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ListTabAction extends Action
{

    /**
     * @var TabRepository
     */
    private $tabRepository;

    public function __construct(LoggerInterface $logger, TabRepository $tabRepository)
    {
        parent::__construct($logger);
        $this->tabRepository = $tabRepository;
    }

    /**
     * @OA\Get(
     *     tags={"tab"},
     *     path="/tabs",
     *     operationId="getTabs",
     *     summary="List all tabs",
     *     @OA\Response(
     *      response="200",
     *      description="List all tabs",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Tab")
     *      )
     *     )
     * )
     */
    protected function action(): Response
    {
        $tabs = $this->tabRepository->findAllTabs();

        $this->logger->info("Tabs list was viewed");

        return $this->respondWithData($tabs);
    }
}
