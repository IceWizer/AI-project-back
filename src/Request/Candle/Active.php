<?php

namespace App\Request\Candle;

use Symfony\Component\Validator\Constraints as Assert;

class Active
{
    public function __construct(
        #[Assert\Count(
            min: 1,
            minMessage: 'ids.min_count',
        )]
        public array $ids,
    ) {
    }
}
