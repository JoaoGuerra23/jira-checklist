<?php

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\Exceptions\BadRequestException;
use App\Domain\Exceptions\NotAllowedException;
use App\Domain\Exceptions\NotFoundException;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use App\Domain\Validation\Validator;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
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

    public function __construct(LoggerInterface $logger, TicketRepository $ticketAuthRepository)
    {
        parent::__construct($logger);
        $this->ticketRepository = $ticketAuthRepository;
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
     *   ),
     *     security={{"bearerAuth":{}}}
     * )
     * @return Response
     * @throws HttpBadRequestException
     * @throws BadRequestException
     * @throws NotAllowedException
     */
    protected function action(): Response
    {
        $currentCode = $this->resolveArg('code');
        $newCode = Validator::getParam($this->request, 'code');


        try {
            if (empty($this->ticketRepository->findTicketByCode($currentCode))) {
                return $this->respondWithNotFound($currentCode);
            }
        } catch (Exception $e) {
        }

        if ($currentCode === $newCode) {
            return $this->respondWithSameResources();
        }

        $this->ticketRepository->updateTicket($newCode, $currentCode);

        $message = "Ticket code " . $currentCode . " updated to " . $newCode;

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
