<?php

namespace App\Domain\Ticket;

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
     * @param TicketDTO $ticketDTO
     * @return void
     */
    public function deleteTicketByCode(TicketDTO $ticketDTO): void;

    /**
     * Update Ticket
     *
     * @param string $parsedBodyCode
     * @param TicketDTO $ticketDTO
     * @return Ticket
     */
    public function updateTicket(string $parsedBodyCode, TicketDTO $ticketDTO): Ticket;

    /**
     * Create a new ticket
     *
     * @param string $code
     * @return Ticket
     */
    public function createNewTicket(string $code): Ticket;
}
