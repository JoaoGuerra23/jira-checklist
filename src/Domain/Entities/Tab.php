<?php

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="tabs")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repositories\TabRepository")
 */
class Tab
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\OneToMany(targetEntity="Section", mappedBy="tabs_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;


}