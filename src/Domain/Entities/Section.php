<?php

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="sections")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repositories\SectionRepository")
 */
class Section
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
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity="Tab", inversedBy="id")
     * @ORM\JoinColumn(name="tabs_id", referencedColumnName="id")
     */
    private $tabs_id;


}