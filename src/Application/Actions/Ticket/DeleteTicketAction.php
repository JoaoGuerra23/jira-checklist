<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

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
     *   path="/tickets/{id}",
     *   operationId="deleteTicket",
     *   summary="Delete Ticket by ID",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Ticket id",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Ticket")
     *   )
     * )
     */
    protected function action(): Response
    {

        $tickets = $this->ticketRepository->deleteTicketById($this->response, $this->args);

        $this->logger->info("Ticket Deleted");

        return $this->respondWithData($tickets);
    }

}