<?php

namespace App\Domain\Auth;

class AuthHelper
{

    public function hash($algo): string
    {
        return md5($algo);
    }
}
