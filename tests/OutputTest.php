<?php

declare(strict_types=1);

use HPT\entity\Product;
use HPT\Output;
use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase
{
    public function testAddProductAndJsonOutput(): void
    {
        $output = new Output();

        $product = new Product('123', 'Test Product', 100, 5);
        $output->addProduct($product);

        $expectedJson = json_encode(['123' => ['price' => 100, 'name' => 'Test Product', 'rating' => 5]], JSON_PRETTY_PRINT);
        $this->assertEquals($expectedJson, $output->getJson());
    }

    public function testAddMultipleProductsAndJsonOutput(): void
    {
        $output = new Output();

        $product1 = new Product('123', 'Test Product', 100, 5);
        $product2 = new Product('456', 'Another Product', 200, 4);

        $output->addProduct($product1);
        $output->addProduct($product2);

        $expectedJson = json_encode([
            '123' => ['price' => 100, 'name' => 'Test Product', 'rating' => 5],
            '456' => ['price' => 200, 'name' => 'Another Product', 'rating' => 4]
        ], JSON_PRETTY_PRINT);
        $this->assertEquals($expectedJson, $output->getJson());
    }

    public function testEmptyOutput(): void
    {
        $output = new Output();
        $this->assertEquals('[]', $output->getJson());
    }

    public function testSpecialCharactersInProductDetails(): void
    {
        $output = new Output();

        $product = new Product('789', 'Product &#', 300, 3);
        $output->addProduct($product);

        $expectedJson = json_encode([
            '789' => ['price' => 300, 'name' => 'Product &#', 'rating' => 3]
        ], JSON_PRETTY_PRINT);
        $this->assertEquals($expectedJson, $output->getJson());
    }
}