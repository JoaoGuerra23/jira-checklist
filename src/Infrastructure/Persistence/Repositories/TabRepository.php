<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Tab\TabDTO;
use App\Domain\Entities\Tab\Tab;
use App\Domain\Entities\Tab\TabRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TabRepository implements TabRepositoryInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Tab
     */
    private $tab;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Tab $tab
     */
    public function __construct(EntityManagerInterface $entityManager, Tab $tab)
    {
        $this->entityManager = $entityManager;
        $this->tab = $tab;
    }


    /**
     * Find All Tabs
     *
     * @return Tab[]
     */
    public function findAllTabs(): array
    {

        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('t.id', 't.name')
            ->from(Tab::class, 't')
            ->where('t.deleted_at IS NULL')
            ->orderBy('t.id', 'ASC');

        return $builder->getQuery()->execute();
    }


    /**
     * Find Tab by Name
     *
     * @param string $id
     * @return Tab[]|null
     */
    public function findTabById(string $id): ?array
    {
        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('t.id', 't.name')
                ->from(Tab::class, 't')
                ->where('t.id = :id')
                ->setParameter(':id', $id)
                ->andWhere('t.deleted_at IS NULL')
                ->getQuery()
                ->getSingleResult();
        } catch (Exception $e) {
            return null;
        }
    }


    /**
     * Delete Tab by ID
     *
     * @param string $id
     * @return void
     */
    public function deleteTabById(string $id): void
    {
        $this->entityManager
            ->createQueryBuilder()
            ->update(Tab::class, 't')
            ->set('t.deleted_at', ':value')
            ->setParameter(':value', new DateTime())
            ->where('t.name = :name')
            ->setParameter(':name', $id)
            ->getQuery()
            ->execute();
    }


    /**
     * Update Tab Name
     *
     * @param string $parsedBodyName
     * @param TabDTO $tabDTO
     * @return Tab
     */
    public function updateTab(string $parsedBodyName, TabDTO $tabDTO): Tab
    {

        $tabDTOId = $tabDTO->getId();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Tab::class, 't')
            ->set('t.name', ':value')
            ->setParameter(':value', $parsedBodyName)
            ->where('t.id = :id')
            ->setParameter(':id', $tabDTOId)
            ->getQuery()
            ->getResult();

        $this->tab->setName($parsedBodyName);

        return $this->tab;
    }

    /**
     *
     * Create new Tab
     *
     * @param string $parsedBodyName
     * @return Tab
     */
    public function createNewTab(string $parsedBodyName): Tab
    {
        $this->tab = new Tab();
        $this->tab->setName($parsedBodyName);

        $this->entityManager->persist($this->tab);
        $this->entityManager->flush();

        return $this->tab;
    }
}
