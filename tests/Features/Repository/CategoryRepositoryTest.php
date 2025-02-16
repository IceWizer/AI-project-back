<?php

namespace App\Tests\Features\Repository;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryRepositoryTest extends WebTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testConstruct(): void
    {
        $container = $this->getContainer();

        $categoryRepository = $container->get(CategoryRepository::class);

        $this->assertInstanceOf(CategoryRepository::class, $categoryRepository);
    }
}