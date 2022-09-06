<?php

namespace App\Domain\Ticket;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TicketNotAllowedException extends DomainRecordNotFoundException
{

}