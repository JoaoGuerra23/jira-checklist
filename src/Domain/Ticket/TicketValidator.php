<?php

namespace App\Domain\Ticket;

use App\Infrastructure\Persistence\Repositories\TicketRepository;

class TicketValidator
{
    /**
     * @throws TicketException
     */
    public static function validateTicketCode(string $ticketCode): string
    {
        if (strlen($ticketCode) > 10) {
            throw new TicketException('Not Allowed - Ticket code too big', 405);
        }

        return $ticketCode;
    }

}