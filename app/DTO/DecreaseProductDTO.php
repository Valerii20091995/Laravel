<?php

namespace App\DTO;

class DecreaseProductDTO
{
    public function __construct(
        public int $productId,
    ) {}
}
