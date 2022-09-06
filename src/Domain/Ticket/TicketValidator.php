<?php

namespace App\Domain\Ticket;

class TicketValidator
{

    /**
     * Validation for ticket code Length
     *
     * @param string $ticketCode
     * @return string
     * @throws TicketNotAllowedException
     */
    public static function validateTicketCodeLength(string $ticketCode): string
    {
        if (strlen($ticketCode) >= 10) {
            throw new TicketNotAllowedException('Code Length too big');
        }

        return $ticketCode;
    }


    /**
     * Validation if Ticket Code is inside an Array
     *
     * @param $code
     * @param $array
     * @return mixed|null
     */
    public static function searchForCode($code, $array)
    {
        foreach ($array as $val) {
            if ($val['code'] === $code) {
                return $code;
            }
        }
        return null;
    }

}