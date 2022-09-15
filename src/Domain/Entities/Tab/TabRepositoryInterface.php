<?php

namespace App\Domain\Entities\Tab;

interface TabRepositoryInterface
{

    /**
     * Find All Tabs
     *
     * @return Tab[]
     */
    public function findAllTabs(): array;

    /**
     * Find Tab by Name
     *
     * @param string $id
     * @return Tab[]|null
     */
    public function findTabById(string $id): ?array;

    /**
     * Delete Tab by ID
     *
     * @param string $id
     * @return void
     */
    public function deleteTabById(string $id): void;

    /**
     * Update Tab Name
     *
     * @param string $parsedBodyName
     * @param TabDTO $tabDTO
     * @return Tab
     */
    public function updateTab(string $parsedBodyName, TabDTO $tabDTO): Tab;

    /**
     *
     * Create new Tab
     *
     * @param string $parsedBodyName
     * @return Tab
     */
    public function createNewTab(string $parsedBodyName): Tab;
}
