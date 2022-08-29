<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Domain\DTOs\SectionDTO;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class DeleteSectionAction extends Action
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
     * @OA\Delete(
     *   tags={"section"},
     *   path="/sections/{subject}",
     *   operationId="deleteSection",
     *   summary="Delete Section by Subject",
     *   @OA\Parameter(
     *          name="subject",
     *          in="path",
     *          required=true,
     *          description="Section Subject",
     *          @OA\Schema(
     *              type="string"
     *          )
     *   ),
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

        if (empty($this->sectionRepository->findSectionBySubject($sectionDTO))){
            return $this->respondWithNotFound($sectionSubject);
        }

        $this->sectionRepository->deleteSectionBySubject($sectionDTO);

        $message = "Subject " . $sectionSubject . " Deleted.";

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
