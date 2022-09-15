<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Section\SectionDTO;
use App\Domain\Entities\Section\Section;
use App\Domain\Entities\Section\SectionRepositoryInterface;
use App\Validation\Validator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class SectionRepository implements SectionRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Section
     */
    private $section;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Section $section
     */
    public function __construct(EntityManagerInterface $entityManager, Section $section)
    {
        $this->entityManager = $entityManager;
        $this->section = $section;
    }


    /**
     * Find All Sections
     *
     * @return Section[]
     */
    public function findAllSections(): array
    {

        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('s.id, s.subject, s.tabsId')
            ->from(Section::class, 's')
            ->where('s.deleted_at IS NULL')
            ->orderBy('s.id', 'ASC');

        return $builder->getQuery()->execute();
    }


    /**
     *
     * Find Section by ID
     *
     * @param SectionDTO $sectionDTO
     * @return Section[]|null
     */
    public function findSectionById(SectionDTO $sectionDTO): ?array
    {
        $id = $sectionDTO->getId();

        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('s.id', 's.subject, s.tabsId')
                ->from(Section::class, 's')
                ->where('s.id = :id')
                ->setParameter(':id', $id)
                ->andWhere('s.deleted_at IS NULL')
                ->getQuery()
                ->getSingleResult();
        } catch (Exception $e) {
            return null;
        }
    }


    /**
     * Delete Section by ID
     *
     * @param SectionDTO $sectionDTO
     * @return void
     */
    public function deleteSectionById(SectionDTO $sectionDTO): void
    {
        $id = $sectionDTO->getId();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Section::class, 's')
            ->set('s.deleted_at', ':value')
            ->setParameter(':value', new DateTime())
            ->where('s.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->execute();
    }


    /**
     * Update Section Subject
     *
     * @param string $parsedBodyId
     * @param SectionDTO $sectionDTO
     * @return Section
     */
    public function updateSectionId(string $parsedBodyId, SectionDTO $sectionDTO): Section
    {
        $id = $sectionDTO->getId();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Section::class, 's')
            ->set('s.subject', ':value')
            ->setParameter(':value', $parsedBodyId)
            ->where('s.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getResult();

        $this->section->setSubject($parsedBodyId);

        return $this->section;
    }


    /**
     *
     * @param array $parsedBody
     * @return Section
     */
    public function createNewSection(array $parsedBody): Section
    {
        $this->section = new Section();

        $this->section->setSubject($parsedBody['subject']);
        $this->section->setTabsId($parsedBody['tabs_id']);

        $this->entityManager->persist($this->section);
        $this->entityManager->flush();

        return $this->section;
    }
}
