<?php

namespace App\Domain\Entities\Section;

interface SectionRepositoryInterface
{
    /**
     * Find All Sections
     *
     * @return Section[]
     */
    public function findAllSections(): array;

    /**
     *
     * Find Section by ID
     *
     * @param SectionDTO $sectionDTO
     * @return Section[]|null
     */
    public function findSectionById(SectionDTO $sectionDTO): ?array;

    /**
     * Delete Section by ID
     *
     * @param SectionDTO $sectionDTO
     * @return void
     */
    public function deleteSectionById(SectionDTO $sectionDTO): void;

    /**
     * Update Section Subject
     *
     * @param string $parsedBodyId
     * @param SectionDTO $sectionDTO
     * @return Section
     */
    public function updateSectionId(string $parsedBodyId, SectionDTO $sectionDTO): Section;

    /**
     *
     * @param array $parsedBody
     * @return Section
     */
    public function createNewSection(array $parsedBody): Section;
}
