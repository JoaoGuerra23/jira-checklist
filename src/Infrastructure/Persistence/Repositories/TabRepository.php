<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Tab;
use Doctrine\ORM\EntityManagerInterface;
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
     * Find Tab by ID
     *
     * @param array $args
     * @return array
     */
    public function findTabById(array $args): array
    {
        $id = $args['id'];

        return $this->entityManager
            ->createQueryBuilder()
            ->select('t.id', 't.name')
            ->from(Tab::class, 't')
            ->where('t.id = :id')
            ->setParameter(':id', $id)
            ->andWhere('t.deleted_at IS NULL')
            ->getQuery()
            ->execute();
    }


    /**
     * Delete Tab by ID
     *
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteTabById(Response $response, array $args): Response
    {
        $id = $args['id'];
        $column = 't.deleted_at';
        $value = new \DateTime();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Tab::class, 't')
            ->set($column, ':value')
            ->setParameter(':value', $value)
            ->where('t.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->execute();

        return $response->withStatus(200, 'Ticket deleted');
    }


    /**
     * Update Tab Name
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function updateTabName(Request $request, Response $response): Response
    {

        $data = $request->getParsedBody();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Tab::class, 't')
            ->set('t.name', ':value')
            ->setParameter(':value', $data['name'])
            ->where('t.id = :id')
            ->setParameter(':id', $data['id'])
            ->getQuery()
            ->getResult();

        return $response->withStatus(200, 'OK - Tab Edited');
    }


    /**
     *
     * Create new Tab
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function createNewTab(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $this->tab = new Tab();
        $this->tab->setName($data['name']);

        $this->entityManager->persist($this->tab);
        $this->entityManager->flush();

        return $response->withStatus(200, 'OK - Tab Created');
    }
}
