<?php

namespace App\Tests\Entity;

use App\Entity\Candle;
use App\Entity\Category;
use App\Entity\Image;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CandleTest extends TestCase
{
    public function testGetId(): void
    {
        $candle = new Candle();
        $this->assertNull($candle->getId());
    }

    public function testSetAndGetTitle(): void
    {
        $candle = new Candle();
        $candle->setTitle('Test Title');
        $this->assertEquals('Test Title', $candle->getTitle());
    }

    public function testSetAndGetShortDescription(): void
    {
        $candle = new Candle();
        $candle->setShortDescription('Test Short Description');
        $this->assertEquals('Test Short Description', $candle->getShortDescription());
    }

    public function testSetAndGetDescription(): void
    {
        $candle = new Candle();
        $candle->setDescription('Test Description');
        $this->assertEquals('Test Description', $candle->getDescription());
    }

    public function testSetAndGetPrice(): void
    {
        $candle = new Candle();
        $candle->setPrice(100);
        $this->assertEquals(100, $candle->getPrice());
    }

    public function testSetAndGetStock(): void
    {
        $candle = new Candle();
        $candle->setStock(10);
        $this->assertEquals(10, $candle->getStock());
    }

    public function testSetAndIsActive(): void
    {
        $candle = new Candle();
        $candle->setActive(true);
        $this->assertTrue($candle->isActive());
    }

    public function testAddAndRemoveCategory(): void
    {
        $candle = new Candle();
        $category = new Category();
        $category->setName('Test Category');

        $candle->addCategory($category);
        $this->assertCount(1, $candle->getCategories());
        $this->assertTrue($candle->getCategories()->contains($category));

        $candle->removeCategory($category);
        $this->assertCount(0, $candle->getCategories());
        $this->assertFalse($candle->getCategories()->contains($category));
    }

    public function testAddAndRemoveImage(): void
    {
        $candle = new Candle();
        $image = new Image();
        $image->setName('Test Image');
        $image->setPath('/path/to/image');

        $candle->addImage($image);
        $this->assertCount(1, $candle->getImages());
        $this->assertTrue($candle->getImages()->contains($image));
        $this->assertEquals($candle, $image->getCandle());

        $candle->removeImage($image);
        $this->assertCount(0, $candle->getImages());
        $this->assertFalse($candle->getImages()->contains($image));
        $this->assertNull($image->getCandle());
    }
}