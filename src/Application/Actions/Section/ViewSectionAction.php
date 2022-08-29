<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Domain\DTOs\SectionDTO;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
use App\Infrastructure\Persistence\Repositories\TicketRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

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
     *   path="/sections/{subject}",
     *   operationId="getSection",
     *   summary="Get Section by Subject",
     *   @OA\Parameter(
     *          name="subject",
     *          in="path",
     *          required=true,
     *          description="Section subject",
     *          @OA\Schema(
     *              type="string"
     *   )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Section")
     *   )
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $sectionSubject = $this->resolveArg('subject');

        $sectionDTO = new SectionDTO($sectionSubject);

        $section = $this->sectionRepository->findSectionBySubject($sectionDTO);

        if (empty($section)) {
            return $this->respondWithNotFound($sectionSubject);
        }

        $this->logger->info("Section " . $sectionSubject . " was viewed.");

        return $this->respondWithData($section);
    }
}
