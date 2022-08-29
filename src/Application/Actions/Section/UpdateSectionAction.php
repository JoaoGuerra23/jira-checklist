<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Domain\DTOs\SectionDTO;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class UpdateSectionAction extends Action
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
     * @OA\Patch(
     *   path="/sections/{subject}",
     *   tags={"section"},
     *   path="/sections/{subject}",
     *   operationId="editSection",
     *   summary="Edit Section Subject",
     *         @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="subject",
     *                     type="string"
     *                 ),
     *                 example={"subject": "Subject1"}
     *             )
     *         )
     *     ),
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
        $currentSubject = $this->resolveArg('subject');

        $sectionDTO = new SectionDTO($currentSubject);

        if (empty($this->sectionRepository->findSectionBySubject($sectionDTO))) {
            return $this->respondWithNotFound($sectionDTO->getSubject());
        }

        $section = $this->sectionRepository->updateSectionSubject($this->request, $sectionDTO);

        $updatedSubject = $section->jsonSerialize()['subject'];

        $message = "Section Subject " . $currentSubject . " updated to " . $updatedSubject;

        if ($currentSubject == $updatedSubject) {
            return $this->respondWithSameResources();
        }

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
