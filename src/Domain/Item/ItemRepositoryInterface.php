<?php

namespace App\Domain\Item;

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
     * @param ItemDTO $itemDTO
     * @return Item[]|null
     */
    public function findItemById(ItemDTO $itemDTO): ?array;

    /**
     * Delete Item by id
     *
     * @param ItemDTO $itemDTO
     * @return void
     */
    public function deleteItemById(ItemDTO $itemDTO): void;

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
    public function createNewItem(array $parsedBody): ?Item;

}