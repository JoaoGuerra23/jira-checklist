<?php

namespace App\Domain\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

/**
 *
 * @ORM\Table(name="items")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repositories\ItemRepository")
 *
 * @OA\Schema(
 *     description="Item Model",
 *     title="Item"
 * )
 *
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
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
     * @OA\Property(type="string", description="Item Name", title="Item Name")
     *
     * @var string
     *
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="id")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", format="int64", description="Status ID", title="Status ID")
     *
     * @var int
     *
     */
    private $status_id;

    /**
     * @ORM\Column(type="integer")
     *
     * @OA\Property(type="integer", format="int64", description="Owner ID", title="Owner ID")
     *
     * @var int
     *
     */
    private $owner_id;

    /**
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="id")
     * @ORM\JoinColumn(name="tickets_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", format="int64", description="Tickets ID", title="Tickets ID")
     *
     * @var int
     *
     */
    private $tickets_id;

    /**
     * @ORM\Column(type="datetime", nullable=false, name="date")
     *
     * @OA\Property(type="datetime", description="Created Date", title="Created Date")
     *
     * @var DateTime
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Section", inversedBy="id")
     * @ORM\JoinColumn(name="sections_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", format="int64", description="Sections ID", title="Sections ID")
     *
     * @var int
     *
     */
    private $sections_id;

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

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->status_id;
    }

    /**
     * @param int $status_id
     */
    public function setStatusId(int $status_id): void
    {
        $this->status_id = $status_id;
    }

    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->owner_id;
    }

    /**
     * @param int $owner_id
     */
    public function setOwnerId(int $owner_id): void
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return int
     */
    public function getTicketsId(): int
    {
        return $this->tickets_id;
    }

    /**
     * @param int $tickets_id
     */
    public function setTicketsId(int $tickets_id): void
    {
        $this->tickets_id = $tickets_id;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getSectionsId(): int
    {
        return $this->sections_id;
    }

    /**
     * @param int $sections_id
     */
    public function setSectionsId(int $sections_id): void
    {
        $this->sections_id = $sections_id;
    }



}