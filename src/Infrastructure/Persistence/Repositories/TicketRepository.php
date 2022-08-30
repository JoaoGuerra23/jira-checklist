<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\DTOs\TicketDTO;
use App\Domain\Entities\Ticket;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

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
     * Find Ticket by Code
     *
     * @param TicketDTO $ticketDTO
     * @return Ticket[]|null
     */
    public function findTicketByCode(TicketDTO $ticketDTO): ?array
    {
        $ticketDTOCode = $ticketDTO->getCode();

        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('t.id', 't.code')
                ->from(Ticket::class, 't')
                ->where('t.code = :code')
                ->setParameter(':code', $ticketDTOCode)
                ->andWhere('t.deleted_at IS NULL')
                ->getQuery()
                ->getSingleResult();
        } catch (Exception $e) {
            return null;
        }
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
     * @param string $parsedBodyCode
     * @param TicketDTO $ticketDTO
     * @return Ticket
     */
    public function updateTicketCode(string $parsedBodyCode, TicketDTO $ticketDTO): Ticket
    {
        $ticketDTOCode = $ticketDTO->getCode();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Ticket::class, 't')
            ->set('t.code', ':value')
            ->setParameter(':value', $parsedBodyCode)
            ->where('t.code = :code')
            ->setParameter(':code', $ticketDTOCode)
            ->getQuery()
            ->getResult();

        $this->ticket->setCode($parsedBodyCode);

        return $this->ticket;
    }

    /**
     * Create a new Ticket
     *
     * @param string $parsedBodyCode
     * @return Ticket
     */
    public function createNewTicket(string $parsedBodyCode): Ticket
    {
        $this->ticket = new Ticket();
        $this->ticket->setCode($parsedBodyCode);

        $this->entityManager->persist($this->ticket);
        $this->entityManager->flush();

        return $this->ticket;
    }
}
