<?php

namespace App\Tests\Features\Controller;

use App\Controller\CandleController;
use App\Controller\CategoryController;
use App\Entity\Category;
use App\Repository\CandleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class CategoryControllerTest extends WebTestCase
{
    public static KernelBrowser $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = static::createClient();
        self::$client->enableProfiler();

        $logger = self::getContainer()->get(LoggerInterface::class);
        $logger->info('CategoryControllerTest started');

        // Remove all categories
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->createQuery('DELETE FROM ' . Category::class)->execute();
    }

    /**
     * @covers \App\Controller\CategoryController::__construct
     * @runInSeparateProcess
     */
    public function testConstruct(): void
    {
        $container = $this->getContainer();

        $categoryController = $container->get(CategoryController::class);

        $this->assertInstanceOf(CategoryController::class, $categoryController);
    }

    /**
     * @covers \App\Controller\CategoryController::index
     */
    public function testIndex(): void
    {
        self::$client->request('GET', '/api/v1/categories/');

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());

        $content = self::$client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);

        $responseData = json_decode($content, true);

        $this->assertIsArray($responseData);
        $this->assertEquals($responseData, []);
    }

    /**
     * @covers \App\Controller\CategoryController::create
     * @depends testIndex
     */
    public function testCreate(): void
    {
        self::$client->jsonRequest('POST', '/api/v1/categories/', [
            'name' => 'Category C 1',
        ]);

        $this->assertEquals(201, self::$client->getResponse()->getStatusCode());

        $content = self::$client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);
        $this->assertJsonStringEqualsJsonString(
            '{"message":"Category added successfully"}',
            $content
        );
    }

    /**
     * @covers \App\Controller\CategoryController::index
     * @depends testCreate
     */
    public function testIndexAfterCreate(): void
    {
        self::$client->request('GET', '/api/v1/categories/');

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());

        $content = self::$client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);

        $responseData = json_decode($content, true);

        $this->assertIsArray($responseData);
        $this->assertCount(1, $responseData);
        $this->assertArrayHasKey('id', $responseData[0]);
        $this->assertArrayHasKey('name', $responseData[0]);

        $this->assertEquals('Category C 1', $responseData[0]['name']);
    }
}
