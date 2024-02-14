<?php

namespace HPT\entity;

class Product
{
    private string $productCode;
    private ?string $name;
    private ?int $price;
    private ?int $rating;

    public function __construct(string $productCode, ?string $name, ?int $price, ?int $rating)
    {
        $this->productCode = $productCode;
        $this->name = $name;
        $this->price = $price;
        $this->rating = $rating;
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

    public function getRating(): ?int
    {
        return $this->rating;
    }

}