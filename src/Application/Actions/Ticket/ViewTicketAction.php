<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;


class ViewTicketAction extends Action
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
     * @OA\Get(
     *   tags={"ticket"},
     *   path="/tickets/{id}",
     *   operationId="getTicket",
     *   summary="Get Ticket by ID",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Ticket id",
     *          @OA\Schema(
     *              type="integer"
     *   )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="A single ticket",
     *     @OA\JsonContent(ref="#/components/schemas/Ticket")
     *   )
     * )
     */
    protected function action(): Response
    {
        $ticket = $this->ticketRepository->findTicketById($this->args);

        return $this->respondWithData($ticket);
    }


}