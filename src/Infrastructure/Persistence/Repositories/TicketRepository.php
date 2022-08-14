<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Application\Actions\Action;
use App\Domain\DTOs\TicketDTO;
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
     * @param TicketDTO $ticketDTO
     * @return Ticket
     */
    public function deleteTicketByCode(TicketDTO $ticketDTO): Ticket
    {
        $DTOCode = $ticketDTO->getCode();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Ticket::class, 't')
            ->set('t.deleted_at', ':value')
            ->setParameter(':value', new DateTime())
            ->where('t.code = :code')
            ->setParameter(':code', $DTOCode)
            ->getQuery()
            ->execute();

        $this->ticket->setCode($DTOCode);

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

        $body = $request->getParsedBody();

        $code = $request->getAttribute('code');

        $builder = $this->entityManager
            ->createQueryBuilder()
            ->update(Ticket::class, 't')
            ->set('t.code', ':value')
            ->setParameter(':value', $body['code'])
            ->where('t.code = :code')
            ->setParameter(':code', $code);

        $builder->getQuery()->getResult();

        $this->ticket->setCode($body['code']);

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
