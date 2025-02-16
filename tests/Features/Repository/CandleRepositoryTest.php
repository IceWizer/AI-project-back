<?php

namespace App\Tests\Features\Repository;
use App\Entity\Candle;
use App\Repository\CandleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CandleRepositoryTest extends WebTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public static function setUpBeforeClass(): void
    {
        // Remove all categories
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->createQuery('DELETE FROM ' . Candle::class)->execute();
    }

    public function testConstruct(): void
    {
        $container = $this->getContainer();

        $candleRepository = $container->get(CandleRepository::class);

        $this->assertInstanceOf(CandleRepository::class, $candleRepository);
    }

    /**
     * @covers \App\Repository\CandleRepository::findActiveCandleByFilter
     * @return array{candles: Candle[], categories: Category[]} Candle and Category entities
     */
    public function testFindActiveCandlesNoCandles(): array
    {
        $container = $this->getContainer();

        /**
         * @var CandleRepository $candleRepository
         */
        $candleRepository = $container->get(CandleRepository::class);
        $em = $this->getContainer()->get(EntityManagerInterface::class);

        $candles = $candleRepository->findActiveCandleByFilter();

        $this->assertIsArray($candles);
        $this->assertCount(0, $candles);

        $category1 = new \App\Entity\Category();
        $category1->setName('Test Category 1');

        $category2 = new \App\Entity\Category();
        $category2->setName('Test Category 2');

        $candle1 = new \App\Entity\Candle();
        $candle1->setTitle('Test Candle Title');
        $candle1->setDescription('Test Candle Description');
        $candle1->setShortDescription('Test Candle Short Description');
        $candle1->setPrice(1000);
        $candle1->setStock(10);
        $candle1->setActive(true);
        $candle1->addCategory($category2);

        $candle2 = new \App\Entity\Candle();
        $candle2->setTitle('Test Candle Title 2');
        $candle2->setDescription('Test Candle Description 2');
        $candle2->setShortDescription('Test Candle Short Description 2');
        $candle2->setPrice(2000);
        $candle2->setStock(20);
        $candle2->setActive(false);
        $candle2->addCategory($category1);
        $candle2->addCategory($category2);

        $candle3 = new \App\Entity\Candle();
        $candle3->setTitle('Test Candle Title 3');
        $candle3->setDescription('Test Candle Description 3');
        $candle3->setShortDescription('Test Candle Short Description 3');
        $candle3->setPrice(3000);
        $candle3->setStock(30);
        $candle3->setActive(true);
        $candle3->addCategory($category1);

        $em->persist($category1);
        $em->persist($category2);
        $em->persist($candle1);
        $em->persist($candle2);
        $em->persist($candle3);
        $em->flush();

        return ["candles" => [$candle1, $candle2, $candle3], "categories" => [$category1, $category2]];
    }

    /**
     * @covers \App\Repository\CandleRepository::findActiveCandleByFilter
     * @depends testFindActiveCandlesNoCandles
     * 
     * @param array{candles: Candle[], categories: Category[]} $persistedCandlesCategories Candle and Category entities
     */
    public function testFindActiveCandlesWithCandles(array $persistedCandlesCategories): void
    {
        [$candle1, $candle2, $candle3] = $persistedCandlesCategories["candles"];
        $container = $this->getContainer();

        /**
         * @var CandleRepository $candleRepository
         */
        $candleRepository = $container->get(CandleRepository::class);

        $candles = $candleRepository->findActiveCandleByFilter();

        $this->assertIsArray($candles);
        $this->assertCount(2, $candles);

        $candleIds = array_map(function (Candle $candle) {
            return $candle->getId()->toRfc4122();
        }, $candles);

        $this->assertContains($candle1->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle2->getId()->toRfc4122(), $candleIds);
        $this->assertContains($candle3->getId()->toRfc4122(), $candleIds);
    }

    /**
     * @covers \App\Repository\CandleRepository::findActiveCandleByFilter
     * @depends testFindActiveCandlesNoCandles
     *
     * @param array{candles: Candle[], categories: Category[]} $persistedCandlesCategories
     */
    public function testFindActiveCandlesWithCandlesAndFilterTitle(array $persistedCandlesCategories): void
    {
        [$candle1, $candle2, $candle3] = $persistedCandlesCategories["candles"];
        $container = $this->getContainer();

        /**
         * @var CandleRepository $candleRepository
         */
        $candleRepository = $container->get(CandleRepository::class);

        $candles = $candleRepository->findActiveCandleByFilter("Test Candle Title");

        $this->assertIsArray($candles);
        $this->assertCount(2, $candles);

        $candleIds = array_map(function (Candle $candle) {
            return $candle->getId()->toRfc4122();
        }, $candles);

        $this->assertContains($candle1->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle2->getId()->toRfc4122(), $candleIds);
        $this->assertContains($candle3->getId()->toRfc4122(), $candleIds);


        $candles = $candleRepository->findActiveCandleByFilter("Test Candle Title 3");

        $this->assertIsArray($candles);
        $this->assertCount(1, $candles);

        $candleIds = array_map(function ($candle) {
            return $candle->getId()->toRfc4122();
        }, $candles);

        $this->assertNotContains($candle1->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle2->getId()->toRfc4122(), $candleIds);
        $this->assertContains($candle3->getId()->toRfc4122(), $candleIds);


        $candles = $candleRepository->findActiveCandleByFilter("Test Candle Title 2");

        $this->assertIsArray($candles);
        $this->assertCount(0, $candles);

        $candleIds = array_map(function ($candle) {
            return $candle->getId()->toRfc4122();
        }, $candles);

        $this->assertNotContains($candle1->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle2->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle3->getId()->toRfc4122(), $candleIds);
    }

    /**
     * @covers \App\Repository\CandleRepository::findActiveCandleByFilter
     * @depends testFindActiveCandlesNoCandles
     *
     * @param array{candles: Candle[], categories: Category[]} $persistedCandlesCategories
     */
    public function testFindActiveCandlesWithCandlesAndFilterCategory(array $persistedCandlesCategories): void
    {
        [$candle1, $candle2, $candle3] = $persistedCandlesCategories["candles"];
        [$category1, $category2] = $persistedCandlesCategories["categories"];
        $container = $this->getContainer();

        /**
         * @var CandleRepository $candleRepository
         */
        $candleRepository = $container->get(CandleRepository::class);

        $candles = $candleRepository->findActiveCandleByFilter(categoryId: $category1->getId());

        $this->assertIsArray($candles);
        $this->assertCount(1, $candles);

        $candleIds = array_map(function (Candle $candle) {
            return $candle->getId()->toRfc4122();
        }, $candles);

        $this->assertNotContains($candle1->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle2->getId()->toRfc4122(), $candleIds);
        $this->assertContains($candle3->getId()->toRfc4122(), $candleIds);


        $candles = $candleRepository->findActiveCandleByFilter(categoryId: $category2->getId());

        $this->assertIsArray($candles);
        $this->assertCount(1, $candles);

        $candleIds = array_map(function ($candle) {
            return $candle->getId()->toRfc4122();
        }, $candles);

        $this->assertContains($candle1->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle2->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle3->getId()->toRfc4122(), $candleIds);
    }

    /**
     * @covers \App\Repository\CandleRepository::findActiveCandleByFilter
     * @depends testFindActiveCandlesNoCandles
     *
     * @param array{candles: Candle[], categories: Category[]} $persistedCandlesCategories
     */
    public function testFindActiveCandlesWithCandlesAndFilterTitleAndCategory(array $persistedCandlesCategories): void
    {
        [$candle1, $candle2, $candle3] = $persistedCandlesCategories["candles"];
        [$category1, $category2] = $persistedCandlesCategories["categories"];
        $container = $this->getContainer();

        /**
         * @var CandleRepository $candleRepository
         */
        $candleRepository = $container->get(CandleRepository::class);

        $candles = $candleRepository->findActiveCandleByFilter("Test Candle Title", $category1->getId());

        $this->assertIsArray($candles);
        $this->assertCount(1, $candles);

        $candleIds = array_map(function (Candle $candle) {
            return $candle->getId()->toRfc4122();
        }, $candles);

        $this->assertNotContains($candle1->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle2->getId()->toRfc4122(), $candleIds);
        $this->assertContains($candle3->getId()->toRfc4122(), $candleIds);

        $candles = $candleRepository->findActiveCandleByFilter("Test Candle Title 3", $category2->getId());

        $this->assertIsArray($candles);
        $this->assertCount(0, $candles);

        $candleIds = array_map(function (Candle $candle) {
            return $candle->getId()->toRfc4122();
        }, $candles);

        $this->assertNotContains($candle1->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle2->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle3->getId()->toRfc4122(), $candleIds);
    }

    /**
     * @covers \App\Repository\CandleRepository::findByIdIn
     * @depends testFindActiveCandlesNoCandles
     * 
     * @param array{candles: Candle[], categories: Category[]} $persistedCandlesCategories Candle and Category entities
     */
    public function testFindByIdIn(array $persistedCandlesCategories): void
    {
        [$candle1, $candle2, $candle3] = $persistedCandlesCategories["candles"];
        $container = $this->getContainer();

        /**
         * @var CandleRepository $candleRepository
         */
        $candleRepository = $container->get(CandleRepository::class);

        $candles = $candleRepository->findByIdIn([$candle1->getId(), $candle3->getId()]);

        $this->assertIsArray($candles);
        $this->assertCount(2, $candles);

        $candleIds = array_map(function (Candle $candle) {
            return $candle->getId()->toRfc4122();
        }, $candles);

        $this->assertContains($candle1->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle2->getId()->toRfc4122(), $candleIds);
        $this->assertContains($candle3->getId()->toRfc4122(), $candleIds);

        $candles = $candleRepository->findByIdIn([$candle2->getId()]);
        $this->assertIsArray($candles);
        $this->assertCount(1, $candles);

        $candleIds = array_map(function (Candle $candle) {
            return $candle->getId()->toRfc4122();
        }, $candles);

        $this->assertNotContains($candle1->getId()->toRfc4122(), $candleIds);
        $this->assertContains($candle2->getId()->toRfc4122(), $candleIds);
        $this->assertNotContains($candle3->getId()->toRfc4122(), $candleIds);

        $candles = $candleRepository->findByIdIn([$candle1->getId(), $candle2->getId(), $candle3->getId()]);
        $this->assertIsArray($candles);
        $this->assertCount(3, $candles);

        $candleIds = array_map(function (Candle $candle) {
            return $candle->getId()->toRfc4122();
        }, $candles);

        $this->assertContains($candle1->getId()->toRfc4122(), $candleIds);
        $this->assertContains($candle2->getId()->toRfc4122(), $candleIds);
        $this->assertContains($candle3->getId()->toRfc4122(), $candleIds);

        $candles = $candleRepository->findByIdIn([]);
        $this->assertIsArray($candles);
        $this->assertCount(0, $candles);
    }

}