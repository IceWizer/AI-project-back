<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Request\Category\Create;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private EntityManagerInterface $entityManager;
    private CategoryRepository $categoryRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository
    ) {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    public function create(Create $candleRequest): Category
    {
        $category = new Category();
        $category->setName($candleRequest->name);

        $this->entityManager->persist($category);

        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception('Error creating category');
        }
        $this->entityManager->flush();

        return $category;
    }
}
