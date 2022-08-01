<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;


class AddTicketAction extends UserAction
{
    protected function action(): Response
    {
        $ticketId = (int)$this->resolveArg('id');
        $ticketCode = $this->resolveArg('ticketCode');
        $newTicket = $this->userRepository->addTicket($ticketId, $ticketCode);

        return $this->respondWithData($newTicket);
    }
}
