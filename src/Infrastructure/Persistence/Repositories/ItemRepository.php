<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\DTOs\ItemDTO;
use App\Domain\Entities\Item;
use App\Domain\Entities\Ticket;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
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
     * Find Item by Name
     *
     * @param ItemDTO $itemDTO
     * @return Item[]|null
     */
    public function findItemByName(ItemDTO $itemDTO): ?array
    {
        $itemDTOName = $itemDTO->getName();

        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('i.id, i.name, i.statusId, i.ownerId, i.ticketId, i.date, i.sectionId')
                ->from(Item::class, 'i')
                ->where('i.name = :name')
                ->setParameter(':name', $itemDTOName)
                ->andWhere('i.deleted_at IS NULL')
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
     * @param string $parsedBodyName
     * @param ItemDTO $itemDTO
     * @return Item
     */
    public function updateItemName(string $parsedBodyName, ItemDTO $itemDTO): Item
    {
        $itemDTOName = $itemDTO->getName();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Item::class, 'i')
            ->set('i.name', ':value')
            ->setParameter(':value', $parsedBodyName)
            ->where('i.name = :name')
            ->setParameter(':name', $itemDTOName)
            ->getQuery()
            ->getResult();

        $this->item->setName($parsedBodyName);

        return $this->item;
    }


    /**
     *
     * @param array $parsedBody
     * @return Item
     */
    public function createNewItem(array $parsedBody): Item
    {
        $this->item = new Item();
        $this->item->setName($parsedBody['name']);
        $this->item->setDate(new DateTime("now"));
        $this->item->setStatusId($parsedBody['statusId']);
        $this->item->setOwnerId($parsedBody['ownerId']);
        $this->item->setSectionId($parsedBody['sectionId']);
        $this->item->setTicketId($parsedBody['ticketId']);

        $this->entityManager->persist($this->item);
        $this->entityManager->flush();

        return $this->item;
    }
}
