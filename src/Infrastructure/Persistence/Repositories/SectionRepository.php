<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\DTOs\SectionDTO;
use App\Domain\Entities\Section;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Slim\Psr7\Request;

class SectionRepository
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
    public function findSectionBySubject(SectionDTO $sectionDTO): ?array
    {
        $sectionDTOSubject = $sectionDTO->getSubject();

        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('s.id', 's.subject, s.tabsId')
                ->from(Section::class, 's')
                ->where('s.subject = :subject')
                ->setParameter(':subject', $sectionDTOSubject)
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
    public function deleteSectionBySubject(SectionDTO $sectionDTO): void
    {
        $sectionDTOSubject = $sectionDTO->getSubject();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Section::class, 's')
            ->set('s.deleted_at', ':value')
            ->setParameter(':value', new DateTime())
            ->where('s.subject = :subject')
            ->setParameter(':subject', $sectionDTOSubject)
            ->getQuery()
            ->execute();
    }


    /**
     * Update Section Subject
     *
     * @param string $parsedBodySubject
     * @param SectionDTO $sectionDTO
     * @return Section
     */
    public function updateSectionSubject(string $parsedBodySubject, SectionDTO $sectionDTO): Section
    {
        $sectionDTOSubject = $sectionDTO->getSubject();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Section::class, 's')
            ->set('s.subject', ':value')
            ->setParameter(':value', $parsedBodySubject)
            ->where('s.subject = :subject')
            ->setParameter(':subject', $sectionDTOSubject)
            ->getQuery()
            ->getResult();

        $this->section->setSubject($parsedBodySubject);

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
