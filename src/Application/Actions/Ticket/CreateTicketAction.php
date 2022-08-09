<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use phpDocumentor\Reflection\Types\This;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateTicketAction extends Action
{

    private $ticketRepository;

    public function __construct(LoggerInterface $logger, TicketRepository $ticketRepository)
    {
        parent::__construct($logger);
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @OA\Post(
     *     tags={"ticket"},
     *     path="/tickets",
     *     operationId="createTicket",
     *     @OA\Response(
     *      response="200",
     *      description="New ticket created",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Ticket")
     *      )
     *     )
     * )
     */
    protected function action(): Response
    {

        $ticket = $this->ticketRepository->createTicket($this->request);

        $this->logger->info("Ticket Created");

        return $this->respondWithData($ticket);
    }

}