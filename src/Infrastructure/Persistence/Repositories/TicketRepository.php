<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use function DI\string;

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
            ->from(Ticket::class, 't');

        return $builder->getQuery()->execute();
    }

    /**
     * Find one ticket by id
     *
     * @return Ticket[]
     */
    public function findTicketOfId($id): array
    {
        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('t.id', 't.code')
            ->from(Ticket::class, 't')
            ->where('t.id=:id')
            ->setParameter('id', $id);

        return $builder->getQuery()->execute();
    }


    /**
     * Delete ticket by id
     *
     * @param $id
     * @return void
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
     * @param $id
     * @return float|int|mixed|string
     */
    public function editTicketColumn($id)
    {
        $column = 't.code';

        $value = 'EX-PAT';

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
    public function createTicket(): array
    {
        $ticketCode = 'EX-007';

        $this->ticket = new Ticket();
        $this->ticket->setCode($ticketCode);

        $this->entityManager->persist($this->ticket);
        $this->entityManager->flush();

        return json_decode(json_encode($this->ticket), true);

    }


}
