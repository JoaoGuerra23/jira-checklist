<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Request;

class TicketRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @var Ticket
     */
    private $ticket;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Ticket $ticket
     */
    public function __construct(EntityManagerInterface $entityManager, Ticket $ticket)
    {
        $this->entityManager = $entityManager;
        $this->ticket = $ticket;
    }


    /**
     * Find All tickets
     *
     * @return Ticket[]
     */
    public function findAll(): array
    {
        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('t.id', 't.code')
            ->from(Ticket::class, 't')
            ->orderBy('t.id', 'ASC');

        return $builder->getQuery()->execute();
    }

    /**
     * Find one ticket by id
     *
     * @return Ticket[]
     */
    public function findTicketOfId($id): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('t.id', 't.code')
            ->from(Ticket::class, 't')
            ->where('t.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->execute();
    }


    /**
     * Delete ticket by id
     *
     * @param $id
     * @return void
     *
     */
    public function deleteTicketOfId($id): void
    {
        $this->entityManager
            ->createQueryBuilder()
            ->delete(Ticket::class, 't')
            ->where('t.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->execute();

    }


    /**
     * Update a value from table
     *
     * @param Request $request
     * @param array $args
     * @return float|int|mixed|string
     */
    public function editTicketColumn(Request $request, array $args)
    {
        $id = $args['id'];

        $column = 't.code';

        $value = $request->getParsedBody();

        return $this->entityManager
            ->createQueryBuilder()
            ->update(Ticket::class, 't')
            ->set($column, ':value')
            ->setParameter(':value', $value)
            ->where('t.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getResult();
    }


    /**
     *
     * @return Ticket[]
     */
    public function createTicket(Request $request): array
    {
        $body = $request->getParsedBody();

        $ticketArray = array_values($body);

        $this->ticket = new Ticket();
        $this->ticket->setCode($ticketArray[1]);

        $this->entityManager->persist($this->ticket);
        $this->entityManager->flush();

        return json_decode(json_encode($this->ticket), true);

    }


}
