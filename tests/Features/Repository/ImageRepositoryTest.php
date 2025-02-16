<?php

namespace App\Tests\Features\Repository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageRepositoryTest extends WebTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testConstruct(): void
    {
        $container = $this->getContainer();

        $imageRepository = $container->get(ImageRepository::class);

        $this->assertInstanceOf(ImageRepository::class, $imageRepository);
    }
}