<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\Ticket;
use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private $users;

    /**
     * @var Ticket []
     */
    private $tickets;

    /**
     * @param User[]|null $users
     * @param Ticket[]|null $tickets
     */
    public function __construct(array $users = null, array $tickets = null)
    {
        $this->users = $users ?? [
                1 => new User(1, 'bill.gates', 'Bill', 'Gates'),
                2 => new User(2, 'steve.jobs', 'Steve', 'Jobs'),
                3 => new User(3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
                4 => new User(4, 'evan.spiegel', 'Evan', 'Spiegel'),
                5 => new User(5, 'jack.dorsey', 'Jack', 'Dorsey'),
            ];

        $this->tickets = $tickets ?? [
                1 => new Ticket(1, "EX-1234"),
                2 => new Ticket(2, "EX-222222"),
                3 => new Ticket(3, "EX-777")
            ];


    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findAllTickets() : array
    {
        return array_values($this->tickets);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException();
        }

        return $this->users[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function findTicketById(int $id) : Ticket
    {
        if(isset($this->tickets[$id])){
            var_dump('TICKET NOT FOUND');
        }

        return $this->tickets[$id];
    }


    /**
     * @param int $id
     * @param $ticketCode
     * @return Ticket
     */
    public function addTicket(int $id, $ticketCode) : Ticket
    {
        return new Ticket($id, $ticketCode);
    }
}
