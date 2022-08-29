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
    public function findAllTickets(): array
    {
        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('t.id', 't.code')
            ->from(Ticket::class, 't')
            ->where('t.deleted_at IS NULL')
            ->orderBy('t.id', 'ASC');

        //Good practice to limit the result

        return $builder->getQuery()->execute();
    }


    /**
     *
     * Find Ticket by Code
     *
     * @param TicketDTO $ticketDTO
     * @return Ticket[]
     */
    public function findTicketByCode(TicketDTO $ticketDTO): array
    {
        $ticketDTOCode = $ticketDTO->getCode();

        return $this->entityManager
            ->createQueryBuilder()
            ->select('t.id', 't.code')
            ->from(Ticket::class, 't')
            ->where('t.code = :code')
            ->setParameter(':code', $ticketDTOCode)
            ->andWhere('t.deleted_at IS NULL')
            ->getQuery()
            ->execute();
    }


    /**
     * Delete ticket by Code
     *
     * @param TicketDTO $ticketDTO
     */
    public function deleteTicketByCode(TicketDTO $ticketDTO): void
    {
        $ticketDTOCode = $ticketDTO->getCode();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Ticket::class, 't')
            ->set('t.deleted_at', ':value')
            ->setParameter(':value', new DateTime())
            ->where('t.code = :code')
            ->setParameter(':code', $ticketDTOCode)
            ->getQuery()
            ->execute();
    }

    /**
     * Update Ticket Code
     *
     * @param Request $request
     * @param TicketDTO $ticketDTO
     * @return Ticket
     */
    public function updateTicketCode(Request $request, TicketDTO $ticketDTO): Ticket
    {
        $ticketDTOCode = $ticketDTO->getCode();

        $code = $request->getParsedBody()['code'];

        $this->entityManager
            ->createQueryBuilder()
            ->update(Ticket::class, 't')
            ->set('t.code', ':value')
            ->setParameter(':value', $code)
            ->where('t.code = :code')
            ->setParameter(':code', $ticketDTOCode)
            ->getQuery()
            ->getResult();

        $this->ticket->setCode($code);

        return $this->ticket;
    }

    /**
     *
     * @param Request $request
     * @return Ticket
     */
    public function createNewTicket(Request $request): Ticket
    {
        $code = $request->getParsedBody()['code'];

        $this->ticket = new Ticket();
        $this->ticket->setCode($code);

        $this->entityManager->persist($this->ticket);
        $this->entityManager->flush();

        return $this->ticket;
    }
}
