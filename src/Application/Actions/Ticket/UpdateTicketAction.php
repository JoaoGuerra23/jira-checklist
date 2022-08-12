<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\DTOs\TicketDTO;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class UpdateTicketAction extends Action
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
     * @OA\Patch(
     *   path="/tickets/{id}",
     *   tags={"ticket"},
     *   path="/tickets/{id}",
     *   operationId="editTicket",
     *   summary="Edit Ticket Code",
     *         @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="code",
     *                     type="string"
     *                 ),
     *                 example={"id": 1, "code": "EX-1234"}
     *             )
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Edited Ticket",
     *     @OA\JsonContent(ref="#/components/schemas/Ticket")
     *   )
     * )
     */
    protected function action(): Response
    {
        $code = $this->request;

        $ticketDTO = new TicketDTO($code);

        $ticket = $this->ticketRepository->updateTicketCode($code);


        if (empty($ticket)){

            return $this->respondNotFound($code['code']);
        }


        $this->logger->info("Ticket" . $ticket->jsonSerialize()['id'] . " Edited");

        return $this->respondWithData($ticket->jsonSerialize());
    }

}