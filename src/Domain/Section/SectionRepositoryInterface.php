<?php

namespace App\Domain\Section;

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
    public function findSectionBySubject(SectionDTO $sectionDTO): ?array;

    /**
     * Delete Section by ID
     *
     * @param SectionDTO $sectionDTO
     * @return void
     */
    public function deleteSectionBySubject(SectionDTO $sectionDTO): void;

    /**
     * Update Section Subject
     *
     * @param string $parsedBodySubject
     * @param SectionDTO $sectionDTO
     * @return Section
     */
    public function updateSectionSubject(string $parsedBodySubject, SectionDTO $sectionDTO): Section;

    /**
     *
     * @param array $parsedBody
     * @return Section
     */
    public function createNewSection(array $parsedBody): Section;
}
