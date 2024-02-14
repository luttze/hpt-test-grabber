<?php

namespace HPT;

use HPT\entity\Product;

interface IOutput
{
    public function addProduct(Product $product): void;

    public function getJson(): string;
}