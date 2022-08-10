<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Item;
use App\Domain\Entities\Ticket;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ItemRepository
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Item
     */
    private $item;


    /**
     * @param EntityManagerInterface $entityManager
     * @param Item $item
     */
    public function __construct(EntityManagerInterface $entityManager, Item $item)
    {
        $this->entityManager = $entityManager;
        $this->item = $item;
    }

    /**
     * Find All Items
     *
     * @return Item[]
     */
    public function findAllItems(): array
    {
        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('i.id', 'i.name', 'i.date')
            ->from(Item::class, 'i')
            ->where('i.deleted_at IS NULL')
            ->orderBy('i.id', 'ASC');

        return $builder->getQuery()->execute();
    }


    /**
     *
     * Find Item by ID
     *
     * @param array $args
     * @return array
     */
    public function findItemById(array $args): array
    {
        $id = $args['id'];

        return $this->entityManager
            ->createQueryBuilder()
            ->select('i.id', 'i.name', 'i.date')
            ->from(Item::class, 'i')
            ->where('i.id = :id')
            ->setParameter(':id', $id)
            ->andWhere('i.deleted_at IS NULL')
            ->getQuery()
            ->execute();
    }


    /**
     * Delete Item by id
     *
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteItemById(Response $response, array $args): Response
    {
        $id = $args['id'];
        $column = 'i.deleted_at';
        $value = new DateTime();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Item::class, 'i')
            ->set($column, ':value')
            ->setParameter(':value', $value)
            ->where('i.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->execute();

        return $response->withStatus(200, 'Item deleted');
    }


    /**
     * Update Ticket Code
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function updateItemSubject(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Item::class, 'i')
            ->set('i.name', ':value')
            ->setParameter(':value', $data['name'])
            ->where('i.id = :id')
            ->setParameter(':id', $data['id'])
            ->getQuery()
            ->getResult();

        return $response->withStatus(200, 'OK - Item Edited');

    }


    /**
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function createNewItem(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $date = new DateTime();

        $this->item = new Item();
        $this->item->setName($data['name']);
        $this->item->setDate($date);

        $this->entityManager->persist($this->item);
        $this->entityManager->flush();

        return $response->withStatus(201, 'OK - Item Created');

    }

}