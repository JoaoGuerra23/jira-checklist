<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Tab;
use Doctrine\ORM\EntityManagerInterface;

class TabRepository
{

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    /**
     * Find All tickets
     *
     * @return Tab[]
     */
    public function findAll(): array
    {

        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('t.tabs')
            ->from(Tab::class, 't')
            ->setMaxResults(5);

        return $builder->getQuery()->execute();

    }

}