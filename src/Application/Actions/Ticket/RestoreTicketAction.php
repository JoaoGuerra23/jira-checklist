<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Validation\Validator;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class RestoreTicketAction extends Action
{
    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    public function __construct(LoggerInterface $logger, TicketRepository $ticketAuthRepository)
    {
        parent::__construct($logger);
        $this->ticketRepository = $ticketAuthRepository;
    }

    /**
     * @return Response
     * @throws NotFoundException
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $code = $this->resolveArg('code');

        $this->ticketRepository->restoreTicket($code);

        $message = "Ticket " . $code . " restored";

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}