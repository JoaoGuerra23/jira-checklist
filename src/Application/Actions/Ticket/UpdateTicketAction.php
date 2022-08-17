<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\DTOs\DTOFactory;
use App\Domain\DTOs\TicketDTO;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use phpDocumentor\Reflection\Types\This;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

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
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $currentCode = $this->resolveArg('code');

        $ticketDTO = new TicketDTO($currentCode);

        $ticket = $this->ticketRepository->updateTicketCode($this->request, $ticketDTO);

        $updatedCode = $ticket->jsonSerialize()['code'];

        $message = "Ticket code " . $currentCode . " updated to " . $updatedCode;

        if ($currentCode == $updatedCode) {
            return $this->respondWithData("Please choose a code different from the current one");
        }

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
