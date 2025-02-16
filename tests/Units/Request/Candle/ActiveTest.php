<?php

namespace App\Tests\Units\Request\Candle;

use App\Request\Candle\Active;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ActiveTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = $this->getContainer()->get(ValidatorInterface::class);
    }

    public function testValidIds(): void
    {
        $activeRequest = new Active([1, 2, 3]);
        $errors = $this->validator->validate($activeRequest);

        $this->assertCount(0, $errors);
    }

    public function testEmptyIds(): void
    {
        $activeRequest = new Active([]);
        $errors = $this->validator->validate($activeRequest);

        $this->assertCount(1, $errors);
        $this->assertEquals('ids.min_count', $errors[0]->getMessage());
    }

    public function testSingleId(): void
    {
        $activeRequest = new Active([1]);
        $errors = $this->validator->validate($activeRequest);

        $this->assertCount(0, $errors);
    }
}