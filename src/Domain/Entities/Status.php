<?php

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="status")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repositories\StatusRepository")
 */
class Status
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\OneToMany(targetEntity="Item", mappedBy="status_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

}