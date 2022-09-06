<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Ticket\TicketBadRequestException;
use App\Domain\Ticket\TicketDTO;
use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketNotAllowedException;
use App\Domain\Ticket\TicketNotFoundException;
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
                throw new TicketNotFoundException('Ticket Not Found');
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
     *
     * @throws TicketNotAllowedException
     * @throws TicketBadRequestException
     */
    public function updateTicket(string $parsedBodyCode, TicketDTO $ticketDTO): Ticket
    {
        $ticketDTOCode = $ticketDTO->getCode();

        $tickets = $this->findAll();
        $findAllResult = self::searchForCode($parsedBodyCode, $tickets);

        if ($findAllResult === null) {

            $this->entityManager
                ->createQueryBuilder()
                ->update(Ticket::class, 't')
                ->set('t.code', ':value')
                ->setParameter(':value', $parsedBodyCode)
                ->where('t.code = :code')
                ->setParameter(':code', $ticketDTOCode)
                ->getQuery()
                ->getResult();

            $this->ticket->setCode(self::validateTicketCodeLength($parsedBodyCode));

            return $this->ticket;
        }

        throw new TicketBadRequestException('Ticket Already Exists');
    }

    /**
     * Create a new Ticket
     *
     * @param string $code
     * @return Ticket
     *
     * @throws TicketNotAllowedException
     * @throws TicketBadRequestException
     */
    public function createNewTicket(string $code): Ticket
    {
        $tickets = $this->findAll();
        $findAllResult = self::searchForCode($code, $tickets);

        if ($findAllResult === null) {

            $this->ticket = new Ticket();
            $this->ticket->setCode(self::validateTicketCodeLength($code));

            $this->entityManager->persist($this->ticket);
            $this->entityManager->flush();

            return $this->ticket;
        }

        $undeletedTickets = $this->findAllTickets();
        $findUndeletedTicketsResult = self::searchForCode($code, $undeletedTickets);

        if ($findUndeletedTicketsResult === null) {

            $builder = $this->entityManager
                ->createQueryBuilder()
                ->update(Ticket::class, 't')
                ->set('t.deleted_at', ':value')
                ->setParameter(':value', null)
                ->where('t.code = :code')
                ->setParameter(':code', $code);

            $builder->getQuery()->execute();

            $this->ticket->setCode($code);

            return $this->ticket;

        }

        throw new TicketBadRequestException('Ticket Already Exists');
    }

}
