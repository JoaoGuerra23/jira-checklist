<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\Ticket\TicketException;
use App\Domain\Ticket\TicketValidator;
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
     * @throws TicketException
     */
    protected function action(): Response
    {
        $parsedBody = $this->request->getParsedBody();

        $code = reset($parsedBody);

        // TODO validation for a new even if is deleted_at

        $ticketArr = $this->ticketRepository->findAll();

        $key = array_search($code, array_column($ticketArr, 'code'));

        dd($key);


        $ticket = $this->ticketRepository->createNewTicket($code);

        $message = "Ticket " . $ticket->getCode() . " Created.";

        $this->logger->info($message);

        return $this->respondWithData($message, 201);

    }
}
