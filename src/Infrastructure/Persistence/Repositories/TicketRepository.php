<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

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
            ->where('t.deleted_at IS NULL')
            ->orderBy('t.id', 'ASC');

        return $builder->getQuery()->execute();
    }

    /**
     * Find one ticket by id
     *
     * @return Ticket[]
     */
    public function findTicketById(array $args): array
    {
        $id = $args['id'];

        return $this->entityManager
            ->createQueryBuilder()
            ->select('t.id', 't.code')
            ->from(Ticket::class, 't')
            ->where('t.id = :id')
            ->setParameter(':id', $id)
            ->andWhere('t.deleted_at IS NULL')
            ->getQuery()
            ->execute();
    }


    /**
     * Delete ticket by id
     *
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteTicketOfId(Response $response, array $args): Response
    {
        $id = $args['id'];
        $column = 't.deleted_at';
        $value = new \DateTime();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Ticket::class, 't')
            ->set($column, ':value')
            ->setParameter(':value', $value)
            ->where('t.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->execute();

        return $response->withStatus(200, 'Ticket deleted');
    }


    /**
     * Update a value from table
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function editTicketColumn(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Ticket::class, 't')
            ->set('t.code', ':value')
            ->setParameter(':value', $data['code'])
            ->where('t.id = :id')
            ->setParameter(':id', $data['id'])
            ->getQuery()
            ->getResult();

        return $response->withStatus(200, 'OK - Ticket Edited');

    }


    /**
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function createTicket(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $ticket = new Ticket();
        $ticket->setCode($data['code']);

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return $response->withStatus(200, 'OK - Ticket Created');

    }


}
