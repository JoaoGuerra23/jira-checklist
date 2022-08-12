<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Ticket;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Request;


class TicketRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Ticket[]
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
    public function findAllTickets(): array
    {
        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('t.id', 't.code')
            ->from(Ticket::class, 't')
            ->where('t.deleted_at IS NULL')
            ->orderBy('t.id', 'ASC');

        //I Should limit results to prevent sql injection

        return $builder->getQuery()->execute();
    }


    /**
     *
     * Find Ticket by ID
     *
     * @param array $args
     * @return Ticket[]
     */
    public function findTicketByCode(array $args): array
    {
        $code = $args['code'];

        return $this->entityManager
            ->createQueryBuilder()
            ->select('t.id', 't.code')
            ->from(Ticket::class, 't')
            ->where('t.code = :code')
            ->setParameter(':code', $code)
            ->andWhere('t.deleted_at IS NULL')
            ->getQuery()
            ->execute();
    }


    /**
     * Delete ticket by id
     *
     * @param array $args
     * @return Ticket
     */
    public function deleteTicketById(array $args): Ticket
    {
        $id = $args['id'];
        $column = 't.deleted_at';
        $value = new DateTime();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Ticket::class, 't')
            ->set($column, ':value')
            ->setParameter(':value', $value)
            ->where('t.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->execute();

        $this->ticket->setId($args['id']);

        return $this->ticket;

    }


    /**
     * Update Ticket Code
     *
     * @param Request $request
     * @return Ticket
     */
    public function updateTicketCode(Request $request): Ticket
    {
        $data = $request->getParsedBody();

        $builder = $this->entityManager
            ->createQueryBuilder()
            ->update(Ticket::class, 't')
            ->set('t.code', ':value')
            ->setParameter(':value', $data['code'])
            ->where('t.id = :id')
            ->setParameter(':id', $data['id']);

        $builder->getQuery()->getResult();

        $this->ticket->setId($data['id']);
        $this->ticket->setCode($data['code']);

        return $this->ticket;
    }


    /**
     *
     * @param Request $request
     * @return Ticket
     */
    public function createNewTicket(Request $request): Ticket
    {
        $data = $request->getParsedBody();

        $this->ticket = new Ticket();
        $this->ticket->setCode($data['code']);

        $this->entityManager->persist($this->ticket);
        $this->entityManager->flush();

        return $this->ticket;

    }


}
