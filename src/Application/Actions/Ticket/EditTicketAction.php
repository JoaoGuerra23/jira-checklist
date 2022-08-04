<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class EditTicketAction extends Action
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

        $id = (int)$this->resolveArg('id');

        $tickets = $this->ticketRepository->editTicketColumn($id);

        $this->logger->info("Ticket `${id}` Edited");

        return $this->respondWithData($tickets);
    }

}