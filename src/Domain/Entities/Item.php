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
     * @ORM\Column(type="datetime")
     *
     * @OA\Property(description="Created Date", title="Created Date")
     *
     * @var DateTime
     *
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

}