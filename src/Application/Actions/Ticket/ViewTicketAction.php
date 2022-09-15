<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\Exceptions\NotAllowedException;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Validation\Validator;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class ViewTicketAction extends Action
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
     * @OA\Get(
     *   tags={"ticket"},
     *   path="/tickets/{code}",
     *   operationId="getTicket",
     *   summary="Get Ticket by Code",
     *   @OA\Parameter(
     *          name="code",
     *          in="path",
     *          required=true,
     *          description="Ticket Code",
     *          @OA\Schema(
     *              type="string"
     *   )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Ticket")
     *   ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket not Found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *        @OA\Response(
     *         response=405,
     *         description="Not Allowed"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     *
     *
     * @return Response
     * @throws HttpBadRequestException
     * @throws NotAllowedException
     * @throws NotFoundException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    protected function action(): Response
    {
        $ticketCode = $this->resolveArg('code');

        Validator::validateLength($ticketCode);

        $ticket = $this->ticketRepository->findTicketByCode($ticketCode);

        $this->logger->info('Ticket of code ' . $ticketCode . ' was viewed.');

        return $this->respondWithData($ticket);
    }
}
