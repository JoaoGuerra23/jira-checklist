<?php

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;

class Item
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="id")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $owner_id;

    /**
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="id")
     * @ORM\JoinColumn(name="tickets_id", referencedColumnName="id")
     */
    private $tickets_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Section", inversedBy="id")
     * @ORM\JoinColumn(name="sections_id", referencedColumnName="id")
     */
    private $sections_id;

}