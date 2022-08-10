<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateSectionAction extends Action
{

    private $sectionRepository;

    public function __construct(LoggerInterface $logger, SectionRepository $sectionRepository)
    {
        parent::__construct($logger);
        $this->sectionRepository = $sectionRepository;
    }

    /**
     * @OA\Post(
     *     tags={"section"},
     *     path="/sections",
     *     operationId="createSection",
     *     description="Create new Section",
     *     summary="Create a new Section",
     *      @OA\RequestBody(
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
     *                 example={"id": 1, "subject": "Section subject goes here"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="OK",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Section")
     *      )
     *     )
     * )
     */
    protected function action(): Response
    {

        $section = $this->sectionRepository->createNewSection($this->request, $this->response);

        $this->logger->info("Section Created");

        return $this->respondWithData($section, 201);
    }

}