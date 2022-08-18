<?php

namespace App\Domain\DTOs;

use JsonSerializable;

class TicketDTO implements JsonSerializable
{
    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code
        ];
    }
}
