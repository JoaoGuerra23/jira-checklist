<?php

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repositories\TicketRepository")
 */
class Ticket
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
    private $code;


}