<?php

namespace HPT\entity;

class Product
{
    private string $productCode;
    private ?string $name;
    private ?int $price;

    public function __construct(string $productCode, ?string $name, ?int $price)
    {
        $this->productCode = $productCode;
        $this->name = $name;
        $this->price = $price;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

}