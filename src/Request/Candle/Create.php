<?php

namespace App\Request\Candle;

use Symfony\Component\Validator\Constraints as Assert;

class Create
{
    public function __construct(
        #[Assert\NotBlank(message: 'title.not_blank')]
        #[Assert\Length(min: 3, max: 255, minMessage: 'title.min_length', maxMessage: 'title.max_length')]
        public ?string $title,
        #[Assert\NotBlank(message: 'short_description.not_blank')]
        #[Assert\Length(min: 3, max: 255, minMessage: 'short_description.min_length', maxMessage: 'short_description.max_length')]
        public ?string $shortDescription,
        #[Assert\NotBlank(message: 'description.not_blank')]
        #[Assert\Length(min: 3, max: 4095, minMessage: 'description.min_length', maxMessage: 'description.max_length')]
        public ?string $description,
        #[Assert\NotBlank(message: 'price.not_blank')]
        #[Assert\PositiveOrZero(message: 'price.positive')]
        public ?int $price,
        #[Assert\NotBlank(message: 'stock.not_blank')]
        #[Assert\PositiveOrZero(message: 'stock.positive')]
        public ?int $stock,
    ) {
    }
}
