<?php

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

/**
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repositories\TicketRepository")
 *
 * @OA\Schema(
 *     description="Ticket Model",
 *     title="Ticket"
 * )
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="Item", mappedBy="tickets_id")
     *
     * @OA\Property(type="integer", format="int64", description="ID", title="ID")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="code")
     *
     * @OA\Property(type="string", description="Ticket Code", title="Ticket Code")
     *
     * @var string
     */
    private $code;


    /**
     * @ORM\Column(type="datetime", nullable=true, name="deleted_at")
     */
    private $deleted_at;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * @param mixed $deleted_at
     */
    public function setDeletedAt($deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }



}