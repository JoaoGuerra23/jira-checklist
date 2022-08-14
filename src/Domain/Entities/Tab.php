<?php

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

/**
 *
 * @ORM\Table(name="tabs")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repositories\TabRepository")
 *
 * @OA\Schema(
 *     description="Tab Model",
 *     title="Tab"
 * )
 *
 */
class Tab
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\OneToMany(targetEntity="Section", mappedBy="tabs_id")
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
     * @OA\Property(type="string", description="Tab Name", title="Tab Name")
     *
     * @var string
     */
    private $name;

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
}
