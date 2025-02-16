<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Candle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CategoryTest extends TestCase
{
    public function testGetId(): void
    {
        $category = new Category();
        $this->assertNull($category->getId());
    }

    public function testSetAndGetName(): void
    {
        $category = new Category();
        $category->setName('Test Category');
        $this->assertEquals('Test Category', $category->getName());
    }

    public function testAddAndRemoveCandle(): void
    {
        $category = new Category();
        $candle = new Candle();
        $candle->setTitle('Test Candle');

        $category->addCandle($candle);
        $this->assertCount(1, $category->getCandles());
        $this->assertTrue($category->getCandles()->contains($candle));
        $this->assertTrue($candle->getCategories()->contains($category));

        $category->removeCandle($candle);
        $this->assertCount(0, $category->getCandles());
        $this->assertFalse($category->getCandles()->contains($candle));
        $this->assertFalse($candle->getCategories()->contains($category));
    }
}