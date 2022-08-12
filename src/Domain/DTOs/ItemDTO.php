<?php

namespace App\Domain\DTOs;

use DateTime;

class ItemDTO
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var int
     */
    private $owner;

    /**
     * @param string $name
     * @param DateTime $date
     * @param int $owner
     */
    public function __construct(string $name, DateTime $date, int $owner)
    {
        $this->name = $name;
        $this->date = $date;
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getOwner(): int
    {
        return $this->owner;
    }



}