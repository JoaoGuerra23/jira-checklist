<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;




class ListTicketsAction extends Action
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
     *     tags={"ticket"},
     *     path="/tickets",
     *     operationId="getTickets",
     *     summary="List all tickets",
     *     @OA\Response(
     *      response="200",
     *      description="List all tickets",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Ticket")
     *      )
     *     )
     * )
     */
     protected function action(): Response
    {
        $tickets = $this->ticketRepository->findAllTickets();

        $this->logger->info("Tickets list was viewed");

        return $this->respondWithData($tickets);
    }

}
