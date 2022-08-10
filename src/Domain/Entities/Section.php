<?php

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;
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
class Section
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
     * @OA\Property(type="string", description="Section Subject", title="Section Subject")
     *
     * @var string
     *
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity="Tab", inversedBy="id")
     * @ORM\JoinColumn(name="tabs_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", format="int64", description="Tabs ID", title="Tabs ID")
     *
     * @var int
     *
     */
    private $tabs_id;

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
        return $this->tabs_id;
    }

    /**
     * @param int $tabs_id
     */
    public function setTabsId(int $tabs_id): void
    {
        $this->tabs_id = $tabs_id;
    }




}