<?php

namespace App\Controller;

use App\Repository\CandleRepository;
use App\Request\Candle\Active;
use App\Request\Candle\Create;
use App\Service\CandleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1/candles')]
class CandleController extends AbstractController
{
    public function __construct(private CandleService $candleService)
    {
    }

    #[Route('/', name: 'app_candle_index', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function index(CandleRepository $candleRepository): JsonResponse
    {
        $candles = $candleRepository->findActiveCandleByFilter();

        return $this->json($candles, Response::HTTP_OK, [], ['groups' => 'candle-read']);
    }

    #[Route('/', name: 'app_candle_create', methods: ['POST'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function create(#[MapRequestPayload(acceptFormat: "json")] Create $candleRequest): JsonResponse
    {
        $this->candleService->create($candleRequest);

        return $this->json(['message' => 'Candle added successfully'], Response::HTTP_CREATED);
    }

    #[Route('/active', name: 'app_candle_active', methods: ['POST'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function active(#[MapRequestPayload(acceptFormat: "json")] Active $activeRequest): JsonResponse
    {
        $this->candleService->active($activeRequest->ids);

        return $this->json(['message' => 'Candle active status updated successfully'], Response::HTTP_OK);
    }

    #[Route('/{id}/categories', name: 'app_candle_categories_index', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function categories(CandleRepository $candleRepository, string $id): JsonResponse
    {
        $candle = $candleRepository->find($id);

        return $this->json($candle->getCategories(), Response::HTTP_OK, [], ['groups' => 'category-read']);
    }

    #[Route('/{id}/categories/{categoryId}', name: 'app_candle_categories_add', methods: ['POST'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function addCategory(CandleRepository $candleRepository, string $id, string $categoryId): JsonResponse
    {
        $this->candleService->addCategory($id, $categoryId);

        return $this->json(['message' => 'Category added to candle successfully'], Response::HTTP_CREATED);
    }

    #[Route('/{id}/categories/{categoryId}', name: 'app_candle_categories_remove', methods: ['DELETE'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function removeCategory(CandleRepository $candleRepository, string $id, string $categoryId): JsonResponse
    {
        $this->candleService->removeCategory($id, $categoryId);

        return $this->json(['message' => 'Category removed from candle successfully'], Response::HTTP_OK);
    }
}
