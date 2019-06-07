<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tags", name="tags", methods={"GET"})
     */
    public function tags(TagService $tagService): JsonResponse
    {
        return new JsonResponse($tagService->getTagsAsArray(), Response::HTTP_OK);
    }
}