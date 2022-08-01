<?php
declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;


    /**
     * @return Ticket[]
     */
    public function findAllTickets() : array;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserOfId(int $id): User;

    /**
     * @param int $id
     * @return Ticket
     */
    public function findTicketById(int $id) : Ticket;

}
