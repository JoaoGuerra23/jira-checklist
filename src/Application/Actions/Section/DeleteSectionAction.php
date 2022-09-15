<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Domain\Entities\Section\SectionDTO;
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

    public function __construct(LoggerInterface $logger, SectionRepository $sectionAuthRepository)
    {
        parent::__construct($logger);
        $this->sectionRepository = $sectionAuthRepository;
    }

    /**
     * @OA\Delete(
     *   tags={"section"},
     *   path="/sections/{id}",
     *   operationId="deleteSection",
     *   summary="Delete Section by Id",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Section Id",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *   ),
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

        $id = $this->resolveArg('id');

        $sectionDTO = new SectionDTO($id);

        if (empty($this->sectionRepository->findSectionById($sectionDTO))) {
            return $this->respondWithNotFound($id);
        }

        $this->sectionRepository->deleteSectionById($sectionDTO);

        $message = "Subject " . $id . " Deleted.";

        $this->logger->info($message);

        return $this->respondWithData($message);
    }
}
