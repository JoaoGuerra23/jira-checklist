<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

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
     *   path="/sections/{id}",
     *   tags={"section"},
     *   path="/sections/{id}",
     *   operationId="editSection",
     *   summary="Edit Section Subject",
     *         @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="subject",
     *                     type="string"
     *                 ),
     *                 example={"id": 1, "code": "Section subject goes here"}
     *             )
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Section")
     *   )
     * )
     */
    protected function action(): Response
    {
        $subject = $this->sectionRepository->updateSectionSubject($this->request, $this->response);

        $this->logger->info("Section Subject Edited");

        return $this->respondWithData($subject);
    }

}