<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\User;
use Psr\Http\Message\ResponseInterface as Response;

class ListTicketsAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $tickets = $this->userRepository->findAllTickets();

        $this->logger->info("Tickets list was viewed");

        return $this->respondWithData($tickets);
    }

}
