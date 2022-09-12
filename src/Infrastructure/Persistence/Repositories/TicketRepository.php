<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Exceptions\BadRequestException;
use App\Domain\Exceptions\NotAllowedException;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Ticket\TicketDTO;
use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketRepositoryInterface;
use App\Validation\Validator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Respect\Validation\Validator as v;

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
     * @return Ticket|null
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

            // TODO code don't go inside this condition;

            if (empty($result)) {
                throw new NotFoundException('Ticket Not Found', 404);
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
     * @throws NotFoundException
     */
    public function deleteTicketByCode(TicketDTO $ticketDTO): void
    {
        $ticketDTOCode = $ticketDTO->getCode();

        $result = $this->findTicketByCode($ticketDTOCode);

        if (empty($result)) {
            throw new NotFoundException("Ticket not found", 404);
        }

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
     * @throws BadRequestException
     * @throws NotAllowedException
     */
    public function updateTicket(string $parsedBodyCode, TicketDTO $ticketDTO): Ticket
    {
        $ticketDTOCode = $ticketDTO->getCode();

        $tickets = $this->findAll();
        $findAllResult = Validator::validateValue('code', $parsedBodyCode, $tickets);

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

            $this->ticket->setCode(Validator::validateLength($parsedBodyCode));

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

        // TODO restore() method

        throw new BadRequestException('Ticket Already Exists');
    }
}
