<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\Ticket\TicketNotFoundException;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateTicketAction extends Action
{
    private $ticketRepository;

    public function __construct(LoggerInterface $logger, TicketRepository $ticketRepository)
    {
        parent::__construct($logger);
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @OA\Post(
     *     tags={"ticket"},
     *     path="/tickets",
     *     operationId="createTicket",
     *     description="Create new Ticket",
     *     summary="Create a new Ticket",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="code",
     *                     type="string"
     *                 ),
     *                 example={"code": "EX-1234"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="OK",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Ticket")
     *      )
     *     )
     * )
     * @throws TicketNotFoundException
     */
    protected function action(): Response
    {
        $parsedBody = $this->request->getParsedBody();

        $code = reset($parsedBody);

        $ticket = $this->ticketRepository->createNewTicket($code);

        $message = "Ticket " . $ticket->getCode() . " Created.";

        $this->logger->info($message);

        return $this->respondWithData($message, 201);

    }


}
