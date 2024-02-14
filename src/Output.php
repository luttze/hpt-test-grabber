<?php

declare(strict_types=1);

namespace HPT;

use HPT\entity\Product;

class Output implements IOutput
{
    /**
     * @var array<string, array{price: string|null, name: string|null, rating: int|null}>
     */
    private array $products = [];

    public function addProduct(Product $product): void
    {
        $this->products[$product->getProductCode()] = ['price' => $product->getPrice(), 'name' => $product->getName(), 'rating' => $product->getRating()];
    }

    public function getJson(): string
    {
        $json = json_encode($this->products, JSON_PRETTY_PRINT);
        if ($json === false) {
            return '{}';
        }
        return $json;
    }
}
