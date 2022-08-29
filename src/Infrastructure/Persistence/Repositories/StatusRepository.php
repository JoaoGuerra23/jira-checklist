<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\DTOs\StatusDTO;
use App\Domain\Entities\Status;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Request;

class StatusRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Status
     */
    private $status;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Status $status
     */
    public function __construct(EntityManagerInterface $entityManager, Status $status)
    {
        $this->entityManager = $entityManager;
        $this->status = $status;
    }


    /**
     * Find All Status
     *
     * @return Status[]
     */
    public function findAllStatus(): array
    {
        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('s.id', 's.name')
            ->from(Status::class, 's')
            ->where('s.deleted_at IS NULL')
            ->orderBy('s.id', 'ASC');

        return $builder->getQuery()->execute();
    }


    /**
     *
     * Find Status by ID
     *
     * @param StatusDTO $statusDTO
     * @return array
     */
    public function findStatusByName(StatusDTO $statusDTO): array
    {
        $statusDTOName = $statusDTO->getName();

        return $this->entityManager
            ->createQueryBuilder()
            ->select('s.id', 's.name')
            ->from(Status::class, 's')
            ->where('s.name = :name')
            ->setParameter(':name', $statusDTOName)
            ->andWhere('s.deleted_at IS NULL')
            ->getQuery()
            ->execute();
    }


    /**
     * Delete Status by id
     *
     * @param StatusDTO $statusDTO
     * @return void
     */
    public function deleteStatusByName(StatusDTO  $statusDTO): void
    {
        $statusDTOName = $statusDTO->getName();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Status::class, 's')
            ->set('s.deleted_at', ':value')
            ->setParameter(':value', new DateTime())
            ->where('s.name = :name')
            ->setParameter(':name', $statusDTOName)
            ->getQuery()
            ->execute();

    }


    /**
     * Update Status Name
     *
     * @param Request $request
     * @param StatusDTO $statusDTO
     * @return Status
     */
    public function updateStatusName(Request $request, StatusDTO $statusDTO): Status
    {
        $statusDTOName = $statusDTO->getName();

        $name = $request->getParsedBody()['name'];

        $this->entityManager
            ->createQueryBuilder()
            ->update(Status::class, 's')
            ->set('s.name', ':value')
            ->setParameter(':value', $name)
            ->where('s.name = :name')
            ->setParameter(':name', $statusDTOName)
            ->getQuery()
            ->getResult();

        $this->status->setName($name);

        return $this->status;
    }


    /**
     *
     * @param Request $request
     * @return Status
     */
    public function createNewStatus(Request $request): Status
    {
        $name = $request->getParsedBody()['name'];

        $this->status = new Status();
        $this->status->setName($name);

        $this->entityManager->persist($this->status);
        $this->entityManager->flush();

        return $this->status;
    }
}
