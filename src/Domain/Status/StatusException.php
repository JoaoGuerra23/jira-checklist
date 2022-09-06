<?php

namespace App\Domain\Status;

use App\Domain\DomainException\DomainRecordNotFoundException;

class StatusException extends DomainRecordNotFoundException
{

}