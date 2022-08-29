<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Domain\DTOs\TabDTO;
use App\Infrastructure\Persistence\Repositories\TabRepository;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class ViewTabAction extends Action
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
     *   tags={"tab"},
     *   path="/tabs/{id}",
     *   operationId="getTab",
     *   summary="Get Tab by ID",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Tab id",
     *          @OA\Schema(
     *              type="integer"
     *   )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="A single tab",
     *     @OA\JsonContent(ref="#/components/schemas/Tab")
     *   )
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $tabName = $this->resolveArg('name');

        $tabDTO = new TabDTO($tabName);

        $tab = $this->tabRepository->findTabByName($tabDTO);

        if (empty($tab)) {
            return $this->respondWithNotFound($tabName);
        }

        $this->logger->info('Tab ' . $tabName . ' was viewed');

        return $this->respondWithData($tab);
    }
}
