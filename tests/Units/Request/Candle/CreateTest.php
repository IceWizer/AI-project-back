<?php

namespace App\Tests\Units\Request\Candle;

use App\Request\Candle\Create;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = $this->getContainer()->get(ValidatorInterface::class);
    }

    public function testValidCandle(): void
    {
        $createRequest = new Create('Valid Title', 'Valid Short Description', 'Valid Description', 100, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(0, $errors);
    }

    public function testBlankTitle(): void
    {
        $createRequest = new Create('', 'Valid Short Description', 'Valid Description', 100, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(2, $errors);
        $this->assertEquals('title.not_blank', $errors[0]->getMessage());
        $this->assertEquals('title.min_length', $errors[1]->getMessage());
    }

    public function testShortTitle(): void
    {
        $createRequest = new Create('ab', 'Valid Short Description', 'Valid Description', 100, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('title.min_length', $errors[0]->getMessage());
    }

    public function testLongTitle(): void
    {
        $createRequest = new Create(str_repeat('a', 256), 'Valid Short Description', 'Valid Description', 100, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('title.max_length', $errors[0]->getMessage());
    }

    public function testBlankShortDescription(): void
    {
        $createRequest = new Create('Valid Title', '', 'Valid Description', 100, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(2, $errors);
        $this->assertEquals('short_description.not_blank', $errors[0]->getMessage());
        $this->assertEquals('short_description.min_length', $errors[1]->getMessage());
    }

    public function testShortShortDescription(): void
    {
        $createRequest = new Create('Valid Title', 'ab', 'Valid Description', 100, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('short_description.min_length', $errors[0]->getMessage());
    }

    public function testLongShortDescription(): void
    {
        $createRequest = new Create('Valid Title', str_repeat('a', 256), 'Valid Description', 100, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('short_description.max_length', $errors[0]->getMessage());
    }

    public function testBlankDescription(): void
    {
        $createRequest = new Create('Valid Title', 'Valid Short Description', '', 100, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(2, $errors);
        $this->assertEquals('description.not_blank', $errors[0]->getMessage());
        $this->assertEquals('description.min_length', $errors[1]->getMessage());
    }

    public function testShortDescription(): void
    {
        $createRequest = new Create('Valid Title', 'Valid Short Description', 'ab', 100, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('description.min_length', $errors[0]->getMessage());
    }

    public function testLongDescription(): void
    {
        $createRequest = new Create('Valid Title', 'Valid Short Description', str_repeat('a', 4096), 100, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('description.max_length', $errors[0]->getMessage());
    }

    public function testBlankPrice(): void
    {
        $createRequest = new Create('Valid Title', 'Valid Short Description', 'Valid Description', null, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('price.not_blank', $errors[0]->getMessage());
    }

    public function testNegativePrice(): void
    {
        $createRequest = new Create('Valid Title', 'Valid Short Description', 'Valid Description', -1, 10);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('price.positive', $errors[0]->getMessage());
    }

    public function testBlankStock(): void
    {
        $createRequest = new Create('Valid Title', 'Valid Short Description', 'Valid Description', 100, null);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('stock.not_blank', $errors[0]->getMessage());
    }

    public function testNegativeStock(): void
    {
        $createRequest = new Create('Valid Title', 'Valid Short Description', 'Valid Description', 100, -1);
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('stock.positive', $errors[0]->getMessage());
    }
}
