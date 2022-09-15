<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Domain\Entities\Section\SectionDTO;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
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

    public function __construct(LoggerInterface $logger, SectionRepository $sectionAuthRepository)
    {
        parent::__construct($logger);
        $this->sectionRepository = $sectionAuthRepository;
    }

    /**
     * @OA\Get(
     *   tags={"section"},
     *   path="/sections/{id}",
     *   operationId="getSection",
     *   summary="Get Section by Id",
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Section Id",
     *          @OA\Schema(
     *              type="integer"
     *   )
     * ),
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

        $section = $this->sectionRepository->findSectionById($sectionDTO);

        if (empty($section)) {
            return $this->respondWithNotFound($id);
        }

        $this->logger->info("Section " . $id . " was viewed.");

        return $this->respondWithData($section);
    }
}
