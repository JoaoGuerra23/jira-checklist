<?php

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Infrastructure\Persistence\Repositories\SectionRepository;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ListSectionAction extends Action
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
     *     tags={"section"},
     *     path="/sections",
     *     operationId="getSections",
     *     summary="List all Sections",
     *     @OA\Response(
     *      response="200",
     *      description="List all Sections",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/Section")
     *      )
     *     )
     * )
     */
    protected function action(): Response
    {
        $sections = $this->sectionRepository->findAllSections();

        $this->logger->info("Status list was viewed");

        return $this->respondWithData($sections);
    }
}
