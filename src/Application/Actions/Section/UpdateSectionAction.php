<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Domain\Entities\Section\SectionDTO;
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

    public function __construct(LoggerInterface $logger, SectionRepository $sectionAuthRepository)
    {
        parent::__construct($logger);
        $this->sectionRepository = $sectionAuthRepository;
    }

    /**
     * @OA\Patch(
     *   path="/sections/{id}",
     *   tags={"section"},
     *   path="/sections/{id}",
     *   operationId="editSection",
     *   summary="Edit Section Id",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Section Id",
     *          @OA\Schema(
     *              type="integer"
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
     *   ),
     *     security={{"bearerAuth":{}}}
     * )
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $currentId = $this->resolveArg('id');
        $newId = $this->request->getParsedBody()['id'];

        $sectionDTO = new SectionDTO($currentId);

        if (empty($this->sectionRepository->findSectionById($sectionDTO))) {
            return $this->respondWithNotFound($sectionDTO->getId());
        }

        if ($currentId === $newId) {
            return $this->respondWithSameResources();
        }

        $this->sectionRepository->updateSectionId($newId, $sectionDTO);

        $message = "Section Subject " . $currentId . " updated to " . $newId;

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
