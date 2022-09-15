<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
use App\Domain\Validation\Validator;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateSectionAction extends Action
{

    private $sectionRepository;

    public function __construct(LoggerInterface $logger, SectionRepository $sectionAuthRepository)
    {
        parent::__construct($logger);
        $this->sectionRepository = $sectionAuthRepository;
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
     *                     property="subject",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="tabsId",
     *                     type="int"
     *                 ),
     *                 example={"subject": "Subject", "tabs_id": 1}
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
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    protected function action(): Response
    {

        $parsedBody = $this->request->getParsedBody();

        $section = $this->sectionRepository->createNewSection($parsedBody);

        $message = "Section " . $section->getSubject() . " Created.";

        $this->logger->info($message);

        return $this->respondWithData($message, 201);
    }
}
