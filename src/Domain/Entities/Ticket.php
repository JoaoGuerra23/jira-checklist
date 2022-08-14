<?php

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
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
class Ticket implements JsonSerializable
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
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
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code
        ];
    }
}
