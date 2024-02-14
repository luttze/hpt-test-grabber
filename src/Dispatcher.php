<?php

declare(strict_types=1);

namespace HPT;

use Exception;

class Dispatcher
{
    private IGrabber $grabber;
    private IOutput $output;

    public function __construct(IGrabber $grabber, IOutput $output)
    {
        $this->grabber = $grabber;
        $this->output = $output;
    }


    /**
     * @throws Exception
     */
    public function run(): void
    {
        $productCodes = $this->readProductCodes('input.txt');

        foreach ($productCodes as $code) {
            $product = $this->grabber->getProductDetails($code);
            if ($product !== null) {
                $this->output->addProduct($product);
            }
        }

        echo $this->output->getJson();

    }

    /**
     * @return array<string>
     * @throws Exception
     */
    private function readProductCodes(string $filePath): array
    {
        $productCodes = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($productCodes === false) {
            throw new Exception("Failed to read {$filePath}");
        }
        return $productCodes;
    }
}
