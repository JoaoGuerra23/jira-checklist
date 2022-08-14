<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\Status;
use App\Domain\Entities\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class StatusRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Status
     */
    private $status;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Status $status
     */
    public function __construct(EntityManagerInterface $entityManager, Status $status)
    {
        $this->entityManager = $entityManager;
        $this->status = $status;
    }


    /**
     * Find All Status
     *
     * @return Status[]
     */
    public function findAllStatus(): array
    {
        $builder = $this->entityManager
            ->createQueryBuilder()
            ->select('s.id', 's.name')
            ->from(Status::class, 's')
            ->where('s.deleted_at IS NULL')
            ->orderBy('s.id', 'ASC');

        return $builder->getQuery()->execute();
    }


    /**
     *
     * Find Status by ID
     *
     * @param array $args
     * @return array
     */
    public function findStatusById(array $args): array
    {
        $id = $args['id'];

        return $this->entityManager
            ->createQueryBuilder()
            ->select('s.id', 's.name')
            ->from(Status::class, 's')
            ->where('s.id = :id')
            ->setParameter(':id', $id)
            ->andWhere('s.deleted_at IS NULL')
            ->getQuery()
            ->execute();
    }


    /**
     * Delete Status by id
     *
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteStatusById(Response $response, array $args): Response
    {
        $id = $args['id'];
        $column = 's.deleted_at';
        $value = new \DateTime();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Status::class, 's')
            ->set($column, ':value')
            ->setParameter(':value', $value)
            ->where('s.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->execute();

        return $response->withStatus(200, 'Status deleted');
    }


    /**
     * Update Status Name
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function updateStatusName(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $this->entityManager
            ->createQueryBuilder()
            ->update(Status::class, 's')
            ->set('s.name', ':value')
            ->setParameter(':value', $data['name'])
            ->where('s.id = :id')
            ->setParameter(':id', $data['id'])
            ->getQuery()
            ->getResult();

        return $response->withStatus(200, 'OK - Status Updated');
    }


    /**
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function createNewStatus(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $this->status = new Status();
        $this->status->setName($data['name']);

        $this->entityManager->persist($this->status);
        $this->entityManager->flush();

        return $response->withStatus(201, 'OK - Status Created');
    }
}
