<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Exceptions\BadRequestException;
use App\Domain\Exceptions\NotAllowedException;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Entities\Ticket\TicketDTO;
use App\Domain\Entities\Ticket\Ticket;
use App\Domain\Entities\Ticket\TicketRepositoryInterface;
use App\Domain\Validation\Validator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Exception;

class TicketRepository implements TicketRepositoryInterface
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
            ->orderBy('t.code', 'ASC');

        return $builder->getQuery()->execute();
    }

    public function findTicketsPerPage(): QueryBuilder
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('t.code')
            ->distinct()
            ->from(Ticket::class, 't')
            ->where('t.deleted_at IS NULL')
            ->orderBy('t.code', 'ASC');
    }

    /**
     * Find Ticket by Code
     *
     * @param string $ticketCode
     * @return Ticket[]|null
     * @throws NotFoundException
     * @throws NoResultException
     * @throws NonUniqueResultException
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

            $result = $builder->getQuery()->getSingleResult();

        if (empty($result)) {
            throw new NotFoundException('Ticket Not Found', 404);
        }

        return $result;
    }


    /**
     * Delete ticket by Code
     *
     * @param string $code
     * @return void
     * @throws NotFoundException
     */
    public function deleteTicketByCode(string $code): void
    {
        $result = $this->findTicketByCode($code);

        if (empty($result)) {
            throw new NotFoundException("Ticket not found", 404);
        }

        $this->entityManager
            ->createQueryBuilder()
            ->update(Ticket::class, 't')
            ->set('t.deleted_at', ':value')
            ->setParameter(':value', new DateTime())
            ->where('t.code = :code')
            ->setParameter(':code', $code)
            ->getQuery()
            ->execute();
    }

    /**
     * Update Ticket Code
     *
     * @param string $newCode
     * @param string $currentCode
     * @return Ticket
     * @throws BadRequestException
     * @throws NotAllowedException
     */
    public function updateTicket(string $newCode, string $currentCode): Ticket
    {
        $tickets = $this->findAll();
        $findAllResult = Validator::validateValue('code', $newCode, $tickets);

        if ($findAllResult === null) {
            $this->entityManager
                ->createQueryBuilder()
                ->update(Ticket::class, 't')
                ->set('t.code', ':value')
                ->setParameter(':value', $newCode)
                ->where('t.code = :code')
                ->setParameter(':code', $currentCode)
                ->getQuery()
                ->getResult();

            $this->ticket->setCode(Validator::validateLength($newCode));

            return $this->ticket;
        }

        throw new BadRequestException('Ticket Already Exists');
    }


    /**
     * Create a new Ticket
     *
     * @param string $code
     * @return Ticket
     * @throws BadRequestException
     * @throws NotAllowedException
     */
    public function createNewTicket(string $code): Ticket
    {
        $ticketsArray = $this->findAll();
        $validation = Validator::validateValue('code', $code, $ticketsArray);

        if ($validation === null) {
            $this->ticket = new Ticket();
            $this->ticket->setCode(Validator::validateLength($code));

            $this->entityManager->persist($this->ticket);
            $this->entityManager->flush();

            return $this->ticket;
        }

        throw new BadRequestException('Ticket Already Exists');
    }

    // TODO restore() method
    public function restoreTicket(): Ticket
    {
        return new Ticket();
    }
}
