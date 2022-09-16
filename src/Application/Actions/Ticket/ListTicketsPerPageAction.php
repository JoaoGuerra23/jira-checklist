<?php
declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\Exceptions\NotFoundException;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ListTicketsPerPageAction extends Action
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
     * @OA\Get(
     *   tags={"ticket"},
     *   path="/tickets/page/{page}",
     *   operationId="getTicketsByPage",
     *   summary="Get Tickets by Page",
     *   @OA\Parameter(
     *          name="page",
     *          in="path",
     *          required=true,
     *          description="Tickets Page",
     *          @OA\Schema(
     *              type="integer"
     *   )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="OK"
     *   ),
     *     security={{"bearerAuth":{}}}
     * )
     *
     * @throws NotFoundException
     */
    protected function action(): Response
    {
        $builder = $this->ticketRepository->findTicketsPerPage();

        //Items per page
        $pageCount = 3;

        $paginator = new Paginator($builder);

        //Solve a problem with select on query
        $paginator->setUseOutputWalkers(false);

        //Total tickets
        $totalItems = count($paginator);

        $currentPage = ($this->request->getAttribute('page')) ?: 1;
        $totalPageCount = ceil($totalItems / $pageCount);
        $nextPage = (($currentPage < $totalPageCount) ? $currentPage + 1 : $totalPageCount);
        $previousPage = (($currentPage > 1) ? $currentPage - 1 : 1);

        $result = $paginator
            ->getQuery()
            ->setFirstResult($pageCount * ($currentPage - 1))
            ->setMaxResults($pageCount)
            ->getResult();

        $tickets['Tickets'] = $result;

        if (empty($result)){
            throw new NotFoundException('Page not found',404);
        }

        $this->logger->info("Page " . $currentPage ." was viewed");

        return $this->respondWithData($tickets);
    }
}
