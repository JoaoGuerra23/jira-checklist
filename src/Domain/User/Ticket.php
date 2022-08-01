<?php
declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;

class Ticket implements JsonSerializable
{
    private $id;

    private $ticketCode;

    public function __construct(?int $id, string $ticketCode)
    {
        $this->id = $id;
        $this->ticketCode = strtolower($ticketCode);
    }


      #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'ticketCode' => $this->ticketCode,
        ];
    }
}