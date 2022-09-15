<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\Entities\User\UserRepository;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    protected $userRepository;

    public function __construct(LoggerInterface $logger, UserRepository $userAuthRepository)
    {
        parent::__construct($logger);
        $this->userRepository = $userAuthRepository;
    }
}
