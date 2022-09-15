<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Status\StatusDTO;
use App\Domain\Entities\Status\Status;
use App\Domain\Entities\Status\StatusRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class StatusRepository implements StatusRepositoryInterface
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
     * @return Status[]|null
     */
    public function findStatusByName(StatusDTO $statusDTO): ?array
    {
        $statusDTOName = $statusDTO->getName();

        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('s.id', 's.name')
                ->from(Status::class, 's')
                ->where('s.name = :name')
                ->setParameter(':name', $statusDTOName)
                ->andWhere('s.deleted_at IS NULL')
                ->getQuery()
                ->getSingleResult();
        } catch (Exception $e) {
            return null;
        }
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
     * @param string $parsedBodyName
     * @param StatusDTO $statusDTO
     * @return Status
     */
    public function updateStatusName(string $parsedBodyName, StatusDTO $statusDTO): Status
    {
        $statusDTOName = $statusDTO->getName();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Status::class, 's')
            ->set('s.name', ':value')
            ->setParameter(':value', $parsedBodyName)
            ->where('s.name = :name')
            ->setParameter(':name', $statusDTOName)
            ->getQuery()
            ->getResult();

        $this->status->setName($parsedBodyName);

        return $this->status;
    }


    /**
     *
     * @param string $parsedBodyName
     * @return Status
     */
    public function createNewStatus(string $parsedBodyName): Status
    {
        $this->status = new Status();
        $this->status->setName($parsedBodyName);

        $this->entityManager->persist($this->status);
        $this->entityManager->flush();

        return $this->status;
    }
}
