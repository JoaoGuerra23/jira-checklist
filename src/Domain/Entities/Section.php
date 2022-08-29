<?php

namespace App\Domain\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use JsonSerializable;
use OpenApi\Annotations as OA;

/**
 *
 * @ORM\Table(name="sections")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repositories\SectionRepository")
 *
 * @OA\Schema(
 *     description="Section Model",
 *     title="Section"
 * )
 *
 */
class Section implements JsonSerializable
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @ORM\ManyToOne(targetEntity="Tab", inversedBy="id")
     * @ORM\JoinColumn(name="tabs_id", referencedColumnName="id")
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
     * @OA\Property(type="string", description="Section Subject", title="Section Subject")
     *
     * @var string
     *
     */
    private $subject;

    /**
     * @ORM\Column(name="tabs_id", type="integer")
     *
     * @OA\Property(type="integer", format="int64", description="Tab ID", title="Tab ID")
     *
     * @var int
     *
     */
    private $tabsId;

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
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return int
     */
    public function getTabsId(): int
    {
        return $this->tabsId;
    }

    /**
     * @param int $tabsId
     */
    public function setTabsId(int $tabsId): void
    {
        $this->tabsId = $tabsId;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'tabs_id' => $this->tabsId
        ];
    }
}
