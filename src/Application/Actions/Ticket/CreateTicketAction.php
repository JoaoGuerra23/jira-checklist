<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
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
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $ticket = $this->ticketRepository->createTicket($this->request);

        $this->logger->info("Ticket Created");

        return $this->respondWithData($ticket);
    }

}