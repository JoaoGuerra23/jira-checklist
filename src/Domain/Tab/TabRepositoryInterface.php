<?php

namespace App\Domain\Tab;

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
     * @param TabDTO $tabDTO
     * @return Tab[]|null
     */
    public function findTabByName(TabDTO $tabDTO): ?array;

    /**
     * Delete Tab by ID
     *
     * @param TabDTO $tabDTO
     * @return void
     */
    public function deleteTabByName(TabDTO $tabDTO): void;

    /**
     * Update Tab Name
     *
     * @param string $parsedBodyName
     * @param TabDTO $tabDTO
     * @return Tab
     */
    public function updateTabName(string $parsedBodyName, TabDTO $tabDTO): Tab;

    /**
     *
     * Create new Tab
     *
     * @param string $parsedBodyName
     * @return Tab
     */
    public function createNewTab(string $parsedBodyName): Tab;

}