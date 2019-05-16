<?php

declare(strict_types=1);

namespace Tests\ReadModelBundle\Functional\Product\Action;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\ReadModelBundle\Product\Action\ProductActionView;
use Shopsys\ReadModelBundle\Product\Action\ProductActionViewFacade;
use Shopsys\ShopBundle\DataFixtures\Demo\ProductDataFixture;
use Tests\ShopBundle\Test\FunctionalTestCase;

class ProductActionViewFacadeTest extends FunctionalTestCase
{
    public function testGetForSingleProduct(): void
    {
        $productActionViewFacade = $this->getContainer()->get(ProductActionViewFacade::class);
        $domain = $this->getContainer()->get(Domain::class);
        $url = $domain->getUrl();

        $products = [
            $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1'),
            $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '2'),
            $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '3'),
        ];

        $productActionViews = $productActionViewFacade->getForProducts($products);

        $this->assertCount(3, $productActionViews);
        $this->assertInstanceOf(ProductActionView::class, $productActionViews[1]);

        /** @var \Shopsys\ReadModelBundle\Product\Action\ProductActionView[] $productActionViews */
        $this->assertSame(1, $productActionViews[1]->getId());
        $this->assertSame(sprintf('%s/22-sencor-sle-22f46dm4-hello-kitty/', $url), $productActionViews[1]->getDetailUrl());
        $this->assertFalse($productActionViews[1]->isMainVariant());
        $this->assertFalse($productActionViews[1]->isSellingDenied());
    }
}
