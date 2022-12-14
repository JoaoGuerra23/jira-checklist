<?php
declare(strict_types=1);

namespace App\Application\Actions\Status;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\StatusRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ListStatusAction extends Action
{

    /**
     * @var StatusRepository
     */
    private $statusRepository;

    public function __construct(LoggerInterface $logger, StatusRepository $statusAuthRepository)
    {
        parent::__construct($logger);
        $this->statusRepository = $statusAuthRepository;
    }

    /**
     * @OA\Get(
     *     tags={"status"},
     *     path="/status",
     *     operationId="getAllStatus",
     *     summary="List all status",
     *     @OA\Response(
     *      response="200",
     *      description="List all status",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Status")
     *      )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    protected function action(): Response
    {
        $status = $this->statusRepository->findAllStatus();

        $this->logger->info("Status list was viewed");

        return $this->respondWithData($status);
    }
}
