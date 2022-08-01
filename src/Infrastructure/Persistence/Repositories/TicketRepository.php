<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Ticket;
use Doctrine\ORM\EntityManagerInterface;

class TicketRepository
{

    private $repository;

    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Ticket::class);
    }


    public function findAll()
    {

        // $dql = "SELECT * FROM Ticket";

        return $this->repository->findAll();

        $query = $this->entityManager->createQuery($dql);
        $query->setMaxResults(5);
        return $query->getResult();

    }

}