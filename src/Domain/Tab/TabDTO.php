<?php

namespace App\Domain\Tab;

class TabDTO
{
    //TODO status name is not unique - replace by ID
    /**
     * @var string
     */
    private $name;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
