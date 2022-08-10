<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Section;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

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

        //Should I show tab_id?
        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('s.id', 's.subject')
            ->from(Section::class, 's')
            ->where('s.deleted_at IS NULL')
            ->orderBy('s.id', 'ASC');

        return $builder->getQuery()->execute();
    }


    /**
     *
     * Find Section by ID
     *
     * @param array $args
     * @return array
     */
    public function findSectionById(array $args): array
    {
        $id = $args['id'];

        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('s.id', 's.subject')
            ->from(Section::class, 's')
            ->where('s.id = :id')
            ->setParameter(':id', $id)
            ->andWhere('s.deleted_at IS NULL');

        return $builder->getQuery()->execute();
    }


    /**
     * Delete Section by ID
     *
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteSectionById(Response $response, array $args): Response
    {
        $id = $args['id'];
        $column = 's.deleted_at';
        $value = new DateTime();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Section::class, 's')
            ->set($column, ':value')
            ->setParameter(':value', $value)
            ->where('s.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->execute();

        return $response->withStatus(200, 'Ticket deleted');
    }


    /**
     * Update Section Subject
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function updateSectionSubject(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Section::class, 's')
            ->set('s.subject', ':value')
            ->setParameter(':value', $data['subject'])
            ->where('s.id = :id')
            ->setParameter(':id', $data['id'])
            ->getQuery()
            ->getResult();

        return $response->withStatus(200, 'OK - Section Edited');

    }


    /**
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function createNewSection(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $this->section = new Section();
        $this->section->setSubject($data['subject']);

        $this->entityManager->persist($this->section);
        $this->entityManager->flush();

        return $response->withStatus(201, 'OK - Section Created');

    }


}