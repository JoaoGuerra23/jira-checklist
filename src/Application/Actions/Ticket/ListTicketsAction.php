<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;




class ListTicketsAction extends Action
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
        $tickets = $this->ticketRepository->findAll();

        $this->logger->info("Tickets list was viewed");

        return $this->respondWithData($tickets);
    }

}
