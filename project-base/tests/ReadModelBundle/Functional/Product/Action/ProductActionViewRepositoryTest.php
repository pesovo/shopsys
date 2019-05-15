<?php

declare(strict_types=1);

namespace Tests\ReadModelBundle\Functional\Product\Action;

use Shopsys\ReadModelBundle\Product\Action\ProductActionView;
use Shopsys\ReadModelBundle\Product\Action\ProductActionViewRepository;
use Shopsys\ShopBundle\DataFixtures\Demo\ProductDataFixture;
use Tests\ShopBundle\Test\FunctionalTestCase;

class ProductActionViewRepositoryTest extends FunctionalTestCase
{
    public function testGetForSingleProduct(): void
    {
        $productActionViewRepository = $this->getContainer()->get(ProductActionViewRepository::class);

        $products = [
            $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1'),
            $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '2'),
            $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '3'),
        ];

        $productActionViews = $productActionViewRepository->getForProducts($products);

        $this->assertCount(3, $productActionViews);
        $this->assertInstanceOf(ProductActionView::class, $productActionViews[1]);

        /** @var \Shopsys\ReadModelBundle\Product\Action\ProductActionView[] $productActionViews */
        $this->assertSame(1, $productActionViews[1]->getId());
        $this->assertSame('http://webserver:8080/22-sencor-sle-22f46dm4-hello-kitty/', $productActionViews[1]->getDetailUrl());
        $this->assertFalse($productActionViews[1]->isMainVariant());
        $this->assertFalse($productActionViews[1]->isSellingDenied());
    }
}
