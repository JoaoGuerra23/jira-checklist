<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use phpDocumentor\Reflection\Utils;
use Psr\Http\Message\ResponseInterface as Response;

class ViewTicketAction extends UserAction
{

    protected function action(): Response
    {
        $ticketId = (int)$this->resolveArg('id');
        $ticket = $this->userRepository->findTicketById($ticketId);

        $this->logger->info("Ticket of id `${ticketId}`was viewed");

        return $this->respondWithData($ticket);
    }


}