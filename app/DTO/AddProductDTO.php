<?php

namespace App\DTO;

class AddProductDTO
{
    public function __construct(
        public int $productId,
    ) {}
}
