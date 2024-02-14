<?php

namespace HPT;

use HPT\entity\Product;

interface IGrabber
{
    public function getProductDetails(string $productCode): ?Product;
}