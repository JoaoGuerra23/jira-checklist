<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Domain\Section\SectionDTO;
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
     *   @OA\Parameter(
     *          name="subject",
     *          in="path",
     *          required=true,
     *          description="Section Subject",
     *          @OA\Schema(
     *              type="string"
     *          )
     *   ),
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
        $newSubject = $this->request->getParsedBody()['subject'];

        $sectionDTO = new SectionDTO($currentSubject);

        if (empty($this->sectionRepository->findSectionBySubject($sectionDTO))) {
            return $this->respondWithNotFound($sectionDTO->getSubject());
        }

        if ($currentSubject === $newSubject) {
            return $this->respondWithSameResources();
        }

        $this->sectionRepository->updateSectionSubject($newSubject, $sectionDTO);

        $message = "Section Subject " . $currentSubject . " updated to " . $newSubject;

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
