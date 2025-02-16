<?php

namespace App\Tests\Features\Controller;

use App\Controller\CandleController;
use App\Entity\Category;
use App\Repository\CandleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class CandleControllerTest extends WebTestCase
{
    public KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->enableProfiler();
    }

    /**
     * @covers \App\Controller\CandleController::__construct
     */
    public function testConstruct(): void
    {
        $container = $this->getContainer();

        $candleController = $container->get(CandleController::class);

        $this->assertInstanceOf(CandleController::class, $candleController);
    }

    /**
     * @covers \App\Controller\CandleController::index
     */
    public function testIndex(): void
    {
        $this->client->request('GET', '/api/v1/candles/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = $this->client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);

        $responseData = json_decode($content, true);

        $this->assertIsArray($responseData);
        $this->assertEquals($responseData, []);
    }

    /**
     * @covers \App\Controller\CandleController::create
     * @depends testIndex
     */
    public function testCreate(): void
    {
        $this->client->jsonRequest('POST', '/api/v1/candles/', [
            'title' => 'Candle 1',
            'shortDescription' => 'Short Description 1',
            'description' => 'Description 1',
            'price' => 1050,
            'stock' => 10,
        ]);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $content = $this->client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);
        $this->assertJsonStringEqualsJsonString(
            '{"message":"Candle added successfully"}',
            $content
        );
    }

    /**
     * @covers \App\Controller\CandleController::active
     * @depends testCreate
     */
    public function testActivate(): void
    {
        // Get the candle id
        $candleId = $this->getContainer()->get(CandleRepository::class)->findOneBy(['title' => 'Candle 1'])->getId();

        $this->client->jsonRequest('POST', '/api/v1/candles/active', [
            'ids' => [$candleId->toRfc4122()],
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = $this->client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);
        $this->assertJsonStringEqualsJsonString(
            '{"message":"Candle active status updated successfully"}',
            $content
        );
    }

    /**
     * @covers \App\Controller\CandleController::index
     * @depends testActivate
     */
    public function testIndexAfterCreateAndActive(): void
    {
        $this->client->request('GET', '/api/v1/candles/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = $this->client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);

        $responseData = json_decode($content, true);

        $this->assertIsArray($responseData);
        $this->assertCount(1, $responseData);
        $this->assertArrayHasKey('id', $responseData[0]);
        $this->assertArrayHasKey('title', $responseData[0]);
        $this->assertArrayHasKey('shortDescription', $responseData[0]);
        $this->assertArrayNotHasKey('description', $responseData[0]);
        $this->assertArrayHasKey('price', $responseData[0]);
        $this->assertArrayHasKey('stock', $responseData[0]);

        $this->assertEquals('Candle 1', $responseData[0]['title']);
        $this->assertEquals('Short Description 1', $responseData[0]['shortDescription']);
        $this->assertEquals(1050, $responseData[0]['price']);
        $this->assertEquals(10, $responseData[0]['stock']);
    }

    /**
     * @covers \App\Controller\CandleController::categories
     * @depends testIndexAfterCreateAndActive
     */
    public function testCategories(): void
    {
        // Get the candle id
        $candleId = $this->getContainer()->get(CandleRepository::class)->findOneBy(['title' => 'Candle 1'])->getId();

        $this->client->request('GET', '/api/v1/candles/' . $candleId->toRfc4122() . '/categories');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = $this->client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);

        $responseData = json_decode($content, true);

        $this->assertIsArray($responseData);
        $this->assertEquals($responseData, []);
    }

    /**
     * @covers \App\Controller\CandleController::addCategory
     * @depends testCategories
     */
    public function testAddCategory(): void
    {
        $categoryRepository = $this->getContainer()->get(CategoryRepository::class);

        $category = new Category();
        $category->setName('Category 1');

        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        $entityManager->persist($category);
        $entityManager->flush();

        // Get the candle id
        $candleId = $this->getContainer()->get(CandleRepository::class)->findOneBy(['title' => 'Candle 1'])->getId();

        // Get the category id
        $categoryId = $categoryRepository->findOneBy(['name' => 'Category 1'])->getId();

        $this->client->jsonRequest('POST', '/api/v1/candles/' . $candleId->toRfc4122() . '/categories/' . $categoryId->toRfc4122());

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $content = $this->client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);
        $this->assertJsonStringEqualsJsonString(
            '{"message":"Category added to candle successfully"}',
            $content
        );
    }

    /**
     * @covers \App\Controller\CandleController::categories
     * @depends testAddCategory
     */
    public function testCategoriesAfterAddCategory(): void
    {
        // Get the candle id
        $candleId = $this->getContainer()->get(CandleRepository::class)->findOneBy(['title' => 'Candle 1'])->getId();

        $this->client->request('GET', '/api/v1/candles/' . $candleId->toRfc4122() . '/categories');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = $this->client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);

        $responseData = json_decode($content, true);

        $this->assertIsArray($responseData);
        $this->assertCount(1, $responseData);
        $this->assertArrayHasKey('id', $responseData[0]);
        $this->assertArrayHasKey('name', $responseData[0]);

        $this->assertEquals('Category 1', $responseData[0]['name']);
    }

    /**
     * @covers \App\Controller\CandleController::removeCategory
     * @depends testCategoriesAfterAddCategory
     */
    public function testRemoveCategory(): void
    {
        $categoryRepository = $this->getContainer()->get(CategoryRepository::class);

        // Get the candle id
        $candleId = $this->getContainer()->get(CandleRepository::class)->findOneBy(['title' => 'Candle 1'])->getId();

        // Get the category id
        $categoryId = $categoryRepository->findOneBy(['name' => 'Category 1'])->getId();

        $this->client->request('DELETE', '/api/v1/candles/' . $candleId->toRfc4122() . '/categories/' . $categoryId->toRfc4122());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = $this->client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);
        $this->assertJsonStringEqualsJsonString(
            '{"message":"Category removed from candle successfully"}',
            $content
        );
    }

    /**
     * @covers \App\Controller\CandleController::categories
     * @depends testRemoveCategory
     */
    public function testCategoriesAfterRemoveCategory(): void
    {
        // Get the candle id
        $candleId = $this->getContainer()->get(CandleRepository::class)->findOneBy(['title' => 'Candle 1'])->getId();

        $this->client->request('GET', '/api/v1/candles/' . $candleId->toRfc4122() . '/categories');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = $this->client->getResponse()->getContent();
        if ($content === false) {
            $this->fail('The response content is empty');
        }
        $this->assertJson($content);

        $responseData = json_decode($content, true);

        $this->assertIsArray($responseData);
        $this->assertEquals($responseData, []);
    }
}
