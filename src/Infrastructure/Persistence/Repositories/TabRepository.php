<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\DTOs\TabDTO;
use App\Domain\Entities\Tab;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use http\Message;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class TabRepository
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
     * @param TabDTO $tabDTO
     * @return array
     */
    public function findTabByName(TabDTO $tabDTO): array
    {
        $tabDTOName = $tabDTO->getName();

        return $this->entityManager
            ->createQueryBuilder()
            ->select('t.id', 't.name')
            ->from(Tab::class, 't')
            ->where('t.name = :name')
            ->setParameter(':name', $tabDTOName)
            ->andWhere('t.deleted_at IS NULL')
            ->getQuery()
            ->execute();
    }


    /**
     * Delete Tab by ID
     *
     * @param TabDTO $tabDTO
     * @return void
     */
    public function deleteTabByName(TabDTO $tabDTO): void
    {
        $tabDTOName = $tabDTO->getName();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Tab::class, 't')
            ->set('t.deleted_at', ':value')
            ->setParameter(':value', new DateTime())
            ->where('t.name = :name')
            ->setParameter(':name', $tabDTOName)
            ->getQuery()
            ->execute();
    }


    /**
     * Update Tab Name
     *
     * @param Request $request
     * @param TabDTO $tabDTO
     * @return Tab
     */
    public function updateTabName(Request $request, TabDTO $tabDTO): Tab
    {
        $tabDTOName = $tabDTO->getName();

        $body = $request->getParsedBody();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Tab::class, 't')
            ->set('t.name', ':value')
            ->setParameter(':value', $body['name'])
            ->where('t.name = :name')
            ->setParameter(':name', $tabDTOName)
            ->getQuery()
            ->getResult();

        $this->tab->setName($body['name']);

        return $this->tab;
    }


    /**
     *
     * Create new Tab
     *
     * @param Request $request
     * @return Tab
     */
    public function createNewTab(Request $request): Tab
    {
        $body = $request->getParsedBody();

        $this->tab = new Tab();
        $this->tab->setName($body['name']);

        $this->entityManager->persist($this->tab);
        $this->entityManager->flush();

        return $this->tab;
    }
}
