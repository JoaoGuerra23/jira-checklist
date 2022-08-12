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
     *   path="/tickets/{code}",
     *   operationId="getTicket",
     *   summary="Get Ticket by Code",
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
     *
     * {@inheritDoc}
     */
    protected function action(): Response
    {

        $ticketArray = $this->args;

        //$ticketDTO = new TicketDTO($ticketArray);

        $ticket = $this->ticketRepository->findTicketByCode($ticketArray);

        if (empty($ticket)){

            return $this->respondNotFound($ticketArray['code']);
        }

        $this->logger->info('Ticket ' . $ticketArray['code'] . ' was viewed.');

        return $this->respondWithData($ticket);
    }

}