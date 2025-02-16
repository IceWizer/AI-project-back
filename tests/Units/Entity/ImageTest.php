<?php

namespace App\Tests\Entity;

use App\Entity\Image;
use App\Entity\Candle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ImageTest extends TestCase
{
    public function testGetId(): void
    {
        $image = new Image();
        $this->assertNull($image->getId());
    }

    public function testSetAndGetName(): void
    {
        $image = new Image();
        $image->setName('Test Image');
        $this->assertEquals('Test Image', $image->getName());
    }

    public function testSetAndGetPath(): void
    {
        $image = new Image();
        $image->setPath('/path/to/image');
        $this->assertEquals('/path/to/image', $image->getPath());
    }

    public function testSetAndGetCandle(): void
    {
        $image = new Image();
        $candle = new Candle();
        $image->setCandle($candle);
        $this->assertEquals($candle, $image->getCandle());
    }
}