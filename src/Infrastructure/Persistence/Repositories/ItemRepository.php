<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Entities\Item\ItemDTO;
use App\Domain\Entities\Item\Item;
use App\Domain\Entities\Item\ItemRepositoryInterface;
use App\Domain\Entities\Ticket\Ticket;
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
     * @param string $id
     * @return array|null
     */
    public function findItemById(string $id): ?array
    {
        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('i.id, i.name, i.statusId, i.ownerId, t.code as ticketCode, i.date, i.sectionId')
                ->from(Item::class, 'i')
                ->join(Ticket::class, 't')
                ->where('i.id = :id')
                ->setParameter(':id', $id)
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
     * @param string $id
     * @return void
     */
    public function deleteItemById(string $id): void
    {
        $this->entityManager
            ->createQueryBuilder()
            ->update(Item::class, 'i')
            ->set('i.deleted_at', ':value')
            ->setParameter(':value', new DateTime())
            ->where('i.id = :id')
            ->setParameter(':id', $id)
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
            ->join(Ticket::class, 't')
            ->where('i.id = :id')
            ->setParameter(':id', $itemDTOId)
            ->getQuery()
            ->getResult();
    }


    /**
     * @param $itemId
     * @return bool
     */
    public function isDeletedAt($itemId): bool
    {
        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('t.deleted_at')
            ->from(Ticket::class, 't')
            ->where('t.id = :id')
            ->setParameter(':id', $itemId)
            ->andWhere('t.deleted_at IS NOT NULL')
            ->getQuery()
            ->execute();

        if (empty($builder)) {
            return false;
        }

        return true;
    }


    /**
     * @param array $parsedBody
     * @return Item
     * @throws NotFoundException
     */
    public function createNewItem(array $parsedBody): Item
    {
        $this->item = new Item();
        $this->item->setName($parsedBody['name']);
        $this->item->setDate(new DateTime("now"));
        $this->item->setStatusId($parsedBody['statusId']);
        $this->item->setOwnerId($parsedBody['ownerId']);
        $this->item->setSectionId($parsedBody['sectionId']);

        $ticketId = $parsedBody['ticketId'];

        if ($this->isDeletedAt($ticketId)) {
            throw new NotFoundException('Ticket ID not found when creating a Item');
        }

        $this->item->setTicketId($ticketId);

        $this->entityManager->persist($this->item);
        $this->entityManager->flush();

        return $this->item;
    }
}
