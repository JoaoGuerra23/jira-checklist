<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ViewSectionAction extends Action
{

    /**
     * @var SectionRepository
     */
    private $sectionRepository;

    public function __construct(LoggerInterface $logger, SectionRepository $sectionRepository)
    {
        parent::__construct($logger);
        $this->sectionRepository = $sectionRepository;
    }

    /**
     * @OA\Get(
     *   tags={"section"},
     *   path="/sections/{id}",
     *   operationId="getSection",
     *   summary="Get Section by ID",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Section id",
     *          @OA\Schema(
     *              type="integer"
     *   )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Section")
     *   )
     * )
     */
    protected function action(): Response
    {
        $section = $this->sectionRepository->findSectionById($this->args);

        $this->logger->info('Get single Section');

        return $this->respondWithData($section);
    }
}
