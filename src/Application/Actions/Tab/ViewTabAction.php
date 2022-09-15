<?php
declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Domain\Entities\Tab\TabDTO;
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

    public function __construct(LoggerInterface $logger, TabRepository $tabAuthRepository)
    {
        parent::__construct($logger);
        $this->tabRepository = $tabAuthRepository;
    }

    /**
     * @OA\Get(
     *   tags={"tab"},
     *   path="/tabs/{name}",
     *   operationId="getTab",
     *   summary="Get Tab by Name",
     *   @OA\Parameter(
     *          name="name",
     *          in="path",
     *          required=true,
     *          description="Tab Name",
     *          @OA\Schema(
     *              type="string"
     *   )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="A single tab",
     *     @OA\JsonContent(ref="#/components/schemas/Tab")
     *   ),
     *     security={{"bearerAuth":{}}}
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $id = $this->resolveArg('name');

        $tab = $this->tabRepository->findTabById($id);

        if (empty($tab)) {
            return $this->respondWithNotFound($id);
        }

        $this->logger->info('Tab ' . $id . ' was viewed');

        return $this->respondWithData($tab);
    }
}
