<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Request\Category\Create;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1/categories')]
class CategoryController extends AbstractController
{
    private CategoryService $categoryService;
    private CategoryRepository $categoryRepository;

    public function __construct(
        CategoryService $categoryService,
        CategoryRepository $categoryRepository
    ) {
        $this->categoryService = $categoryService;
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/', name: 'app_category', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->findAll();

        return $this->json($categories, Response::HTTP_OK, [], ['groups' => 'category-read']);
    }

    #[Route('/', name: 'app_category_create', methods: ['POST'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function create(#[MapRequestPayload(acceptFormat: "json")] Create $categoryRequest): JsonResponse
    {
        $this->categoryService->create($categoryRequest);

        return $this->json(['message' => 'Category added successfully'], Response::HTTP_CREATED);
    }
}
