<?php

namespace App\Domain\Entities\Item;

interface ItemRepositoryInterface
{
    /**
     * Find All Items
     *
     * @return Item[]
     */
    public function findAllItems(): array;

    /**
     *
     * Find Item by Name
     *
     * @param string $id
     * @return Item[]|null
     */
    public function findItemById(string $id): ?array;

    /**
     * Delete Item by id
     *
     * @param string $id
     * @return void
     */
    public function deleteItemById(string $id): void;

    /**
     * Update Ticket Code
     *
     * @param array $parsedBody
     * @param ItemDTO $itemDTO
     * @return void
     */
    public function updateItem(array $parsedBody, ItemDTO $itemDTO): void;

    /**
     *
     * @param array $parsedBody
     * @return Item
     */
    public function createNewItem(array $parsedBody): Item;
}
