<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\DTOs\TicketDTO;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class DeleteTicketAction extends Action
{

    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    public function __construct(LoggerInterface $logger, TicketRepository $ticketRepository)
    {
        parent::__construct($logger);
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @OA\Delete(
     *   tags={"ticket"},
     *   path="/tickets/{code}",
     *   operationId="deleteTicket",
     *   summary="Delete Ticket by Code",
     *   @OA\Parameter(
     *          name="code",
     *          in="path",
     *          required=true,
     *          description="Ticket Code",
     *          @OA\Schema(
     *              type="string"
     *          )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Ticket")
     *   )
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {

        $code = $this->resolveArg('code');

        $ticketDTO = new TicketDTO($code);

        $ticket = $this->ticketRepository->deleteTicketByCode($ticketDTO);

        $message = "Ticket " . $ticket->jsonSerialize()['code'] . " Deleted";

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
