<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Ticket\TicketDTO;
use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketException;
use App\Domain\Ticket\TicketRepositoryInterface;
use App\Domain\Ticket\TicketValidator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TicketRepository extends TicketValidator implements TicketRepositoryInterface
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
    public function findAll(): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('t.code')
            ->from(Ticket::class, 't')
            ->getQuery()
            ->execute();
    }

    /**
     * Find All tickets - hiding deleted tickets
     *
     * @return Ticket[]
     */
    public function findAllTickets(): array
    {
        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('t.code')
            ->from(Ticket::class, 't')
            ->where('t.deleted_at IS NULL')
            ->orderBy('t.code', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults(100);

        //TODO pagination

        return $builder->getQuery()->execute();
    }


    /**
     * Find Ticket by Code
     *
     * @param string $ticketCode
     * @return Ticket[]|null
     */
    public function findTicketByCode(string $ticketCode): ?array
    {

        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('t.code')
            ->from(Ticket::class, 't')
            ->where('t.code = :code')
            ->setParameter(':code', $ticketCode)
            ->andWhere('t.deleted_at IS NULL');

        try {
            $result = $builder->getQuery()->getSingleResult();

            if (empty($result)) {
                throw new TicketException('Ticket not Found', 404);
            }

        } catch (Exception $e) {
            return null;
        }

        return $result;
    }


    /**
     * Delete ticket by Code
     *
     * @param TicketDTO $ticketDTO
     * @return void
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
    public function updateTicket(string $parsedBodyCode, TicketDTO $ticketDTO): Ticket
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
     *
     * @throws TicketException
     */
    public function createNewTicket(string $parsedBodyCode): Ticket
    {
        $result = $this->findTicketByCode($parsedBodyCode);

        if (empty($result)) {

            $this->ticket = new Ticket();
            $this->ticket->setCode(self::validateTicketCode($parsedBodyCode));

            $this->entityManager->persist($this->ticket);
            $this->entityManager->flush();

            return $this->ticket;
        }

        throw new TicketException('Resource already exists', 409);

    }

}
