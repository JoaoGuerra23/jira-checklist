<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Item\ItemDTO;
use App\Domain\Item\Item;
use App\Domain\Item\ItemRepositoryInterface;
use App\Domain\Ticket\Ticket;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ItemRepository implements ItemRepositoryInterface
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
            ->select('i.id, i.name, i.statusId, i.ownerId, t.code as ticketCode, i.date, i.sectionId')
            ->from(Item::class, 'i')
            ->join(Ticket::class, 't')
            ->where('i.deleted_at IS NULL')
            ->andWhere('i.ticketId = t.id')
            ->orderBy('i.id', 'ASC');

        return $builder->getQuery()->execute();
    }


    /**
     *
     * Find Item by Name
     *
     * @param ItemDTO $itemDTO
     * @return Item[]|null
     */
    public function findItemById(ItemDTO $itemDTO): ?array
    {
        $itemDTOId = $itemDTO->getId();

        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('i.id, i.name, i.statusId, i.ownerId, t.code as ticketCode, i.date, i.sectionId')
                ->from(Item::class, 'i')
                ->join(Ticket::class, 't')
                ->where('i.id = :id')
                ->setParameter(':id', $itemDTOId)
                ->andWhere('i.deleted_at IS NULL')
                ->andWhere('i.ticketId = t.id')
                ->getQuery()
                ->getSingleResult();
        } catch (Exception $e) {
            return null;
        }
    }


    /**
     * Delete Item by id
     *
     * @param ItemDTO $itemDTO
     * @return void
     */
    public function deleteItemById(ItemDTO $itemDTO): void
    {
        $itemDTOId = $itemDTO->getId();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Item::class, 'i')
            ->set('i.deleted_at', ':value')
            ->setParameter(':value', new DateTime())
            ->where('i.id = :id')
            ->setParameter(':id', $itemDTOId)
            ->getQuery()
            ->execute();
    }


    /**
     * Update Ticket Code
     *
     * @param array $parsedBody
     * @param ItemDTO $itemDTO
     * @return void
     */
    public function updateItem(array $parsedBody, ItemDTO $itemDTO): void
    {

        // TODO Updates should consider all the properties added, not only one (ie: item.name)
        // OK - but for t.code is not working because I need to join table

        $itemDTOId = $itemDTO->getId();

        $propertyToUpdate = 'i.' . array_key_first($parsedBody);
        $finalValue = implode($parsedBody);

        $this->entityManager
            ->createQueryBuilder()
            ->update(Item::class, 'i')
            ->set($propertyToUpdate, ':value')
            ->setParameter(':value', $finalValue)
            ->where('i.id = :id')
            ->setParameter(':id', $itemDTOId)
            ->getQuery()
            ->getResult();
    }


    /**
     *
     * @param array $parsedBody
     * @return Item
     */
    public function createNewItem(array $parsedBody): ?Item
    {
        // TODO Check if ticketId is deleted_at - if yes return an error - if not create a new item
        // OK -

        try {
            $deletedAtQuery = $this->entityManager
                ->createQueryBuilder()
                ->select('t.deleted_at')
                ->from(Ticket::class, 't')
                ->where('t.id = :id')
                ->setParameter(':id', $parsedBody['ticketId'])
                ->getQuery()
                ->getSingleResult();
        } catch (Exception $e) {
            return null;
        }

        $this->item = new Item();
        $this->item->setName($parsedBody['name']);
        $this->item->setDate(new DateTime("now"));
        $this->item->setStatusId($parsedBody['statusId']);
        $this->item->setOwnerId($parsedBody['ownerId']);
        $this->item->setSectionId($parsedBody['sectionId']);

        //If ticketId != null exit();
        if (!in_array(null, $deletedAtQuery)) {
            echo 'This ticketId does not exists';
            exit();
        }

        $this->item->setTicketId($parsedBody['ticketId']);

        $this->entityManager->persist($this->item);
        $this->entityManager->flush();

        return $this->item;
    }
}
