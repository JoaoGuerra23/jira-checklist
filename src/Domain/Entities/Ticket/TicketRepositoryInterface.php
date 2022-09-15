<?php

namespace App\Domain\Entities\Ticket;

interface TicketRepositoryInterface
{
    /**
     * Find All Tickets
     *
     * @return Ticket[]
     */
    public function findAll(): array;

    /**
     * Find All Tickets - hiding deleted tickets
     *
     * @return Ticket[]
     */
    public function findAllTickets(): array;

    /**
     * Find Ticket By Code
     *
     * @param string $ticketCode
     * @return array|null
     */
    public function findTicketByCode(string $ticketCode): ?array;

    /**
     * Delete Ticket By Code
     *
     * @param string $code
     * @return void
     */
    public function deleteTicketByCode(string $code): void;

    /**
     * Update Ticket
     *
     * @param string $newCode
     * @param string $currentCode
     * @return Ticket
     */
    public function updateTicket(string $newCode, string $currentCode): Ticket;

    /**
     * Create a new ticket
     *
     * @param string $code
     * @return Ticket
     */
    public function createNewTicket(string $code): Ticket;
}
