<?php

declare(strict_types=1);

namespace Tests\ReadModelBundle\Unit\Product\Action;

use PHPUnit\Framework\TestCase;
use Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Product\Collection\ProductCollectionFacade;
use Shopsys\FrameworkBundle\Model\Product\Product;
use Shopsys\ReadModelBundle\Product\Action\ProductActionView;
use Shopsys\ReadModelBundle\Product\Action\ProductActionViewFactory;
use Shopsys\ReadModelBundle\Product\Action\ProductActionViewRepository;

class ProductActionViewRepositoryTest extends TestCase
{
    public function testGetForProducts(): void
    {
        $productActionViewFactory = new ProductActionViewFactory();

        $domainConfig = new DomainConfig(1, 'http://webserver:8080/', 'shopsys', 'en');

        $domain = $this->createMock(Domain::class);
        $domain->method('getCurrentDomainConfig')->willReturn($domainConfig);

        $productCollectionFacade = $this->createMock(ProductCollectionFacade::class);
        $productCollectionFacade->method('getAbsoluteUrlsIndexedByProductId')->willReturn([
            1 => 'http://http://webserver:8080/product/1',
            2 => 'http://http://webserver:8080/product/2',
            3 => 'http://http://webserver:8080/product/3',
        ]);

        $productActionViewRepository = new ProductActionViewRepository($productCollectionFacade, $domain, $productActionViewFactory);

        $productActionViews = $productActionViewRepository->getForProducts([
            $this->createProductMock(1),
            $this->createProductMock(2),
            $this->createProductMock(3),
        ]);

        $this->assertCount(3, $productActionViews);
        $this->assertEquals(new ProductActionView(2, false, false, 'http://http://webserver:8080/product/2'), $productActionViews[2]);
    }

    /**
     * @param int $id
     * @return \Shopsys\FrameworkBundle\Model\Product\Product
     */
    private function createProductMock(int $id): Product
    {
        $productMock = $this->createMock(Product::class);

        $productMock->method('getId')->willReturn($id);
        $productMock->method('isSellingDenied')->willReturn(false);
        $productMock->method('isMainVariant')->willReturn(false);

        return $productMock;
    }
}
