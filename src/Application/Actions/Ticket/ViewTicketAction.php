<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\Ticket\TicketDTO;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class ViewTicketAction extends Action
{

    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    public function __construct(LoggerInterface $logger, TicketRepository $ticketRepository)
    {
        parent::__construct($logger);
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @OA\Get(
     *   tags={"ticket"},
     *   path="/tickets/{code}",
     *   operationId="getTicket",
     *   summary="Get Ticket by Code",
     *   @OA\Parameter(
     *          name="code",
     *          in="path",
     *          required=true,
     *          description="Ticket Code",
     *          @OA\Schema(
     *              type="string"
     *   )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Ticket")
     *   )
     * )
     *
     *
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {

        $ticketCode = $this->resolveArg('code');

        $ticketDTO = new TicketDTO($ticketCode);

        $ticket = $this->ticketRepository->findTicketByCode($ticketDTO);

        if (empty($ticket)) {
            return $this->respondWithNotFound($ticketCode);
        }

        $this->logger->info('Ticket ' . $ticketCode . ' was viewed.');

        return $this->respondWithData($ticket);
    }
}
