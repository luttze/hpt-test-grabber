<?php

declare(strict_types=1);

use HPT\Dispatcher;
use HPT\entity\Product;
use HPT\IGrabber;
use HPT\IOutput;
use PHPUnit\Framework\TestCase;


class DispatcherTest extends TestCase
{
    private IGrabber $grabberMock;
    private IOutput $outputMock;
    private Dispatcher $dispatcher;
    private string $tempFilePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->grabberMock = $this->createMock(IGrabber::class);
        $this->outputMock = $this->createMock(IOutput::class);
        $this->dispatcher = new Dispatcher($this->grabberMock, $this->outputMock);

        $this->tempFilePath = sys_get_temp_dir() . '/input.txt';
        file_put_contents($this->tempFilePath, "NH-U9S\ninvalidCode\nvalidCode");

        chdir(sys_get_temp_dir());
    }

    protected function tearDown(): void
    {
        unlink($this->tempFilePath);
        parent::tearDown();
    }

    public function testRunProcessesValidAndInvalidProductCodes(): void
    {
        $expectedProduct = new Product('NH-U9S', 'Noctua NH-U9S', 1679, 97);
        $anotherExpectedProduct = new Product('validCode', 'Valid Product', 1234, 88);

        $addProductCallCount = 0;

        $this->grabberMock->method('getProductDetails')
            ->willReturnCallback(function ($code) use ($expectedProduct, $anotherExpectedProduct, &$addProductCallCount) {
                if ($code === 'NH-U9S') return $expectedProduct;
                if ($code === 'validCode') return $anotherExpectedProduct;
                return null;
            });

        $this->outputMock->method('addProduct')
            ->willReturnCallback(function ($product) use ($expectedProduct, $anotherExpectedProduct, &$addProductCallCount) {
                $addProductCallCount++;
            });

        $this->outputMock->expects($this->once())
            ->method('getJson')
            ->willReturn('{"NH-U9S":{"name":"Noctua NH-U9S","price":1679,"rating":97},"validCode":{"name":"Valid Product","price":1234,"rating":88}}');

        $this->dispatcher->run();

        $this->assertEquals(2, $addProductCallCount, "addProduct was not called the expected number of times.");
    }

    public function testRunWithEmptyInputFile(): void
    {
        file_put_contents($this->tempFilePath, "");

        $this->outputMock->expects($this->never())
            ->method('addProduct');

        $this->dispatcher->run();
    }
}
