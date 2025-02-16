<?php

namespace App\Service;

use App\Entity\Candle;
use App\Entity\Category;
use App\Repository\CandleRepository;
use App\Repository\CategoryRepository;
use App\Request\Candle\Create;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

class CandleService
{
    private EntityManagerInterface $entityManager;
    private CandleRepository $candleRepository;
    private CategoryRepository $categoryRepository;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        CandleRepository $candleRepository,
        CategoryRepository $categoryRepository,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->candleRepository = $candleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->logger = $logger;
    }

    public function create(Create $candleRequest): Candle
    {
        $candle = new Candle();
        $candle->setTitle($candleRequest->title);
        $candle->setShortDescription($candleRequest->shortDescription);
        $candle->setDescription($candleRequest->description);
        $candle->setPrice($candleRequest->price);
        $candle->setStock($candleRequest->stock);
        $candle->setActive(false);

        $this->entityManager->persist($candle);

        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception('Error creating candle');
        }
        $this->entityManager->flush();

        return $candle;
    }

    public function active(array $ids): void
    {
        /** @var Candle[] $candles */
        $candles = $this->candleRepository->findByIdIn($ids);

        foreach ($candles as $candle) {
            $candle->setActive(true);
        }

        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception('Error activating candle');
        }
    }

    public function addCategory(string $candleId, string $categoryId): void
    {
        $candle = $this->candleRepository->find($candleId);

        if (!$candle) {
            throw new \Exception('Candle not found');
        }

        $category = $this->categoryRepository->find($categoryId);

        if (!$category) {
            throw new \Exception('Category not found');
        }

        $this->addCategoryStrict($candle, $category);
    }

    private function addCategoryStrict(Candle $candle, Category $category): void
    {
        $candle->addCategory($category);

        $this->entityManager->persist($candle);

        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception('Error adding category to candle');
        }
    }

    public function removeCategory(string $candleId, string $categoryId): void
    {
        $candle = $this->candleRepository->find($candleId);

        if (!$candle) {
            throw new \Exception('Candle not found');
        }

        $category = $this->categoryRepository->find($categoryId);

        if (!$category) {
            throw new \Exception('Category not found');
        }

        $this->removeCategoryStrict($candle, $category);
    }

    private function removeCategoryStrict(Candle $candle, Category $category): void
    {
        $candle->removeCategory($category);

        $this->entityManager->persist($candle);

        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception('Error removing category from candle');
        }
    }
}
