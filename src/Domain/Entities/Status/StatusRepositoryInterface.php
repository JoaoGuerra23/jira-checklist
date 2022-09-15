<?php

namespace App\Domain\Entities\Status;

interface StatusRepositoryInterface
{
    /**
     * Find All Status
     *
     * @return Status[]
     */
    public function findAllStatus(): array;

    /**
     *
     * Find Status by ID
     *
     * @param StatusDTO $statusDTO
     * @return Status[]|null
     */
    public function findStatusByName(StatusDTO $statusDTO): ?array;

    /**
     * Delete Status by id
     *
     * @param StatusDTO $statusDTO
     * @return void
     */
    public function deleteStatusByName(StatusDTO  $statusDTO): void;

    /**
     * Update Status Name
     *
     * @param string $parsedBodyName
     * @param StatusDTO $statusDTO
     * @return Status
     */
    public function updateStatusName(string $parsedBodyName, StatusDTO $statusDTO): Status;

    /**
     *
     * @param string $parsedBodyName
     * @return Status
     */
    public function createNewStatus(string $parsedBodyName): Status;
}
