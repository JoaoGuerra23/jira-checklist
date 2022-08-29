<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\DTOs\ItemDTO;
use App\Domain\Entities\Item;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Request;

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
            ->select('i.id, i.name, i.statusId, i.ownerId, i.ticketId, i.date, i.sectionId')
            ->from(Item::class, 'i')
            ->where('i.deleted_at IS NULL')
            ->orderBy('i.id', 'ASC');

        return $builder->getQuery()->execute();
    }


    /**
     *
     * Find Item by ID
     *
     * @param ItemDTO $itemDTO
     * @return array
     */
    public function findItemByName(ItemDTO $itemDTO): array
    {
        $itemDTOName = $itemDTO->getName();

        return $this->entityManager
            ->createQueryBuilder()
            ->select('i.id, i.name, i.statusId, i.ownerId, i.ticketId, i.date, i.sectionId')
            ->from(Item::class, 'i')
            ->where('i.name = :name')
            ->setParameter(':name', $itemDTOName)
            ->andWhere('i.deleted_at IS NULL')
            ->getQuery()
            ->execute();
    }


    /**
     * Delete Item by id
     *
     * @param ItemDTO $itemDTO
     * @return void
     */
    public function deleteItemByName(ItemDTO $itemDTO): void
    {
        $itemDTOName = $itemDTO->getName();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Item::class, 'i')
            ->set('i.deleted_at', ':value')
            ->setParameter(':value', new DateTime())
            ->where('i.name = :name')
            ->setParameter(':name', $itemDTOName)
            ->getQuery()
            ->execute();
    }


    /**
     * Update Ticket Code
     *
     * @param Request $request
     * @param ItemDTO $itemDTO
     * @return Item
     */
    public function updateItemName(Request $request, ItemDTO $itemDTO): Item
    {
        $itemDTOName = $itemDTO->getName();

        $name = $request->getParsedBody()['name'];

        $this->entityManager
            ->createQueryBuilder()
            ->update(Item::class, 'i')
            ->set('i.name', ':value')
            ->setParameter(':value', $name)
            ->where('i.name = :name')
            ->setParameter(':name', $itemDTOName)
            ->getQuery()
            ->getResult();

        $this->item->setName($name);

        return $this->item;
    }


    /**
     *
     * @param Request $request
     * @return Item
     */
    public function createNewItem(Request $request): Item
    {
        $body = $request->getParsedBody();

        $this->item = new Item();
        $this->item->setName($body['name']);
        $this->item->setDate(new DateTime("now"));
        $this->item->setStatusId($body['statusId']);
        $this->item->setOwnerId($body['ownerId']);
        $this->item->setSectionId($body['sectionId']);
        $this->item->setTicketId($body['ticketId']);

        $this->entityManager->persist($this->item);
        $this->entityManager->flush();

        return $this->item;
    }
}
