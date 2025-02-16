<?php

namespace App\Request\Category;

use Symfony\Component\Validator\Constraints as Assert;

class Create
{
    public function __construct(
        #[Assert\NotBlank(message: 'name.not_blank')]
        #[Assert\Length(min: 3, max: 255, minMessage: 'name.min_length', maxMessage: 'name.max_length')]
        public ?string $name,
    ) {
    }
}
