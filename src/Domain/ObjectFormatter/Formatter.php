<?php

namespace App\Domain\ObjectFormatter;

class Formatter
{
    // TODO format or toArray - Class format

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
