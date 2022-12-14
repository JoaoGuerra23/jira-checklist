<?php

namespace App\Domain\Entities\Status;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use OpenApi\Annotations as OA;

/**
 *
 * @ORM\Table(name="status")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repositories\StatusRepository")
 *
 * @OA\Schema(
 *     description="Status Model",
 *     title="Status"
 * )
 *
 */
class Status implements JsonSerializable
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\OneToMany(targetEntity="Item", mappedBy="id")
     *
     * @OA\Property(type="integer", format="int64", description="ID", title="ID")
     *
     * @var int
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @OA\Property(type="string", description="Status Name", title="Status Name")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="deleted_at")
     *
     * @phpstan-ignore-next-line
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
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
