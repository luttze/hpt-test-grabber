<?php

declare(strict_types=1);

use HPT\CzcGrabber;
use PHPUnit\Framework\TestCase;
use VCR\VCR;

class CzcGrabberTest extends TestCase
{
    protected function setUp(): void
    {
        VCR::turnOn();
        VCR::configure()->setCassettePath('tests/fixtures/vcr');
        VCR::configure()->setMode('once');
    }

    protected function tearDown(): void
    {
        VCR::eject();
        VCR::turnOff();
    }

    public function testGetProductDetailsSuccess()
    {
        VCR::insertCassette('success.yml');

        $grabber = new CzcGrabber();
        $product = $grabber->getProductDetails('NH-U9S');

        $this->assertNotNull($product);
        $this->assertEquals('Noctua NH-U9S', $product->getName());
        $this->assertEquals(1679, $product->getPrice());
        $this->assertEquals(97, $product->getRating());
    }

    public function testGetProductDetailsFailure()
    {
        VCR::insertCassette('failure.yml');

        $grabber = new CzcGrabber();
        $product = $grabber->getProductDetails('invalid_product_code');

        $this->assertNull($product);
    }

    // nenamockovano, takze jen pro ukazku
    /* public function testGetProductDetailsWithMissingInformation()
     {
         VCR::insertCassette('partial_info.yml');

         $grabber = new CzcGrabber();
         $product = $grabber->getProductDetails('partial_info_code');

         $this->assertNotNull($product);
         $this->assertNull($product->getName());
         $this->assertNotNull($product->getPrice());
         $this->assertNotNull($product->getRating());
     }*/
}