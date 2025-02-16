<?php

namespace App\Tests\Units\Request\Category;

use App\Request\Category\Create;
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

    public function testValidName(): void
    {
        $createRequest = new Create('Valid Category Name');
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(0, $errors);
    }

    public function testBlankName(): void
    {
        $createRequest = new Create('');
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(2, $errors);
        $this->assertEquals('name.not_blank', $errors[0]->getMessage());
        $this->assertEquals('name.min_length', $errors[1]->getMessage());
    }

    public function testShortName(): void
    {
        $createRequest = new Create('ab');
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('name.min_length', $errors[0]->getMessage());
    }

    public function testLongName(): void
    {
        $createRequest = new Create(str_repeat('a', 256));
        $errors = $this->validator->validate($createRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('name.max_length', $errors[0]->getMessage());
    }
}