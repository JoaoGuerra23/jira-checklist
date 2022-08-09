<?php

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Repositories\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="Item", mappedBy="tickets_id")
     */

    private $id;

    /**
     * @ORM\Column(type="string", name="code")
     */

    private $code;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }


}