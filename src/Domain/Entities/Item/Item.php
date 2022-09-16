<?php

namespace App\Domain\Entities\Item;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
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
class Item implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="id")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * @ORM\JoinColumn(name="tickets_id", referencedColumnName="id")
     * @ORM\JoinColumn(name="sections_id", referencedColumnName="id")
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
     * @ORM\Column(name="status_id", type="integer")
     *
     * @OA\Property(type="integer", format="int64", description="Status ID", title="Status ID")
     *
     * @var int
     *
     */
    private $statusId;

    /**
     * @ORM\Column(name="owner_id", type="integer")
     *
     * @OA\Property(type="integer", format="int64", description="Owner ID", title="Owner ID")
     *
     * @var int
     *
     */
    private $ownerId;

    /**
     * @ORM\Column(name="tickets_id", type="integer")
     *
     *
     * @OA\Property(type="integer", format="int64", description="Tickets ID", title="Tickets ID")
     *
     * @var int
     *
     */
    private $ticketId;

    /**
     * @ORM\Column(nullable=false, name="date", type="datetime")
     *
     * @OA\Property(type="datetime", description="Created Date", title="Created Date")
     *
     * @var DateTime
     */
    private $date;

    /**
     * @ORM\Column(name="sections_id", type="integer")
     *
     * @OA\Property(type="integer", format="int64", description="Sections ID", title="Sections ID")
     *
     * @var int
     *
     */
    private $sectionId;

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
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
    }

    /**
     * @param int $statusId
     */
    public function setStatusId(int $statusId): void
    {
        $this->statusId = $statusId;
    }

    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    /**
     * @param int $ownerId
     */
    public function setOwnerId(int $ownerId): void
    {
        $this->ownerId = $ownerId;
    }

    /**
     * @return int
     */
    public function getTicketId(): int
    {
        return $this->ticketId;
    }

    /**
     * @param int $ticketId
     */
    public function setTicketId(int $ticketId): void
    {
        $this->ticketId = $ticketId;
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
    public function getSectionId(): int
    {
        return $this->sectionId;
    }

    /**
     * @param int $sectionId
     */
    public function setSectionId(int $sectionId): void
    {
        $this->sectionId = $sectionId;
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
