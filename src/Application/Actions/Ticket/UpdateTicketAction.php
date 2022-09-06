<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\Ticket\TicketDTO;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
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
     *   path="/tickets/{code}",
     *   tags={"ticket"},
     *   path="/tickets/{code}",
     *   operationId="editTicket",
     *   summary="Edit Ticket Code",
     *   @OA\Parameter(
     *          name="code",
     *          in="path",
     *          required=true,
     *          description="Ticket Code",
     *          @OA\Schema(
     *              type="string"
     *          )
     *   ),
     *    @OA\RequestBody(
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
        $currentCode = $this->resolveArg('code');

        $parsedBody = $this->getFormData();
        //reset() - return the first value from array
        $newCode = reset($parsedBody);

        $ticketDTO = new TicketDTO($currentCode);

        if (empty($parsedBody)){
            return $this->respondWithData('Empty body - please insert the Ticket Code');
        }

        if (empty($this->ticketRepository->findTicketByCode($currentCode))) {
            return $this->respondWithNotFound($ticketDTO->getCode());
        }

        if ($currentCode === $newCode) {
            return $this->respondWithSameResources();
        }

        $this->ticketRepository->updateTicket($newCode, $ticketDTO);

        $message = "Ticket code " . $currentCode . " updated to " . $newCode;

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
