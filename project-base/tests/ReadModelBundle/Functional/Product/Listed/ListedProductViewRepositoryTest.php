<?php

namespace Tests\ReadModelBundle\Functional\Product\Listed;

use Shopsys\FrameworkBundle\Model\Product\Filter\ProductFilterData;
use Shopsys\FrameworkBundle\Model\Product\Listing\ProductListOrderingConfig;
use Shopsys\ReadModelBundle\Product\Listed\ListedProductViewInterface;
use Shopsys\ReadModelBundle\Product\Listed\ListedProductViewRepository;
use Tests\ShopBundle\Test\FunctionalTestCase;

class ListedProductViewRepositoryTest extends FunctionalTestCase
{
    public function testGetForAccessories(): void
    {
        /** @var \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewRepository $listedProductViewRepository */
        $listedProductViewRepository = $this->getContainer()->get(ListedProductViewRepository::class);

        $listedProductViews = $listedProductViewRepository->getForAccessories(1);

        $this->assertCount(2, $listedProductViews);

        $this->assertArrayHasKey(24, $listedProductViews);
        $this->assertArrayHasKey(13, $listedProductViews);

        $this->assertInstanceOf(ListedProductViewInterface::class, $listedProductViews[24]);
        $this->assertInstanceOf(ListedProductViewInterface::class, $listedProductViews[13]);

        $this->assertEquals('Kabel HDMI A - HDMI A M/M 2m gold-plated connectors High Speed HD', $listedProductViews[24]->getName());
        $this->assertEquals('Defender 2.0 SPK-480', $listedProductViews[13]->getName());
    }

    public function testGetPaginatedForBrand(): void
    {
        /** @var \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewRepository $listedProductViewRepository */
        $listedProductViewRepository = $this->getContainer()->get(ListedProductViewRepository::class);

        $paginationResults = $listedProductViewRepository->getPaginatedForBrand(1, ProductListOrderingConfig::ORDER_BY_NAME_ASC, 1, 10);
        $listedProductViews = $paginationResults->getResults();

        $this->assertCount(1, $listedProductViews);

        $this->assertArrayHasKey(5, $listedProductViews);

        $this->assertInstanceOf(ListedProductViewInterface::class, $listedProductViews[5]);
    }

    public function testGetPaginatedForFilteredSearch(): void
    {
        /** @var \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewRepository $listedProductViewRepository */
        $listedProductViewRepository = $this->getContainer()->get(ListedProductViewRepository::class);
        $emptyFilterData = new ProductFilterData();

        $paginationResults = $listedProductViewRepository->getPaginatedForFilteredSearch('kitty', $emptyFilterData, ProductListOrderingConfig::ORDER_BY_NAME_ASC, 1, 10);
        $listedProductViews = $paginationResults->getResults();

        $this->assertArrayHasKey(1, $listedProductViews);

        $this->assertInstanceOf(ListedProductViewInterface::class, $listedProductViews[1]);

        $this->assertEquals('22" Sencor SLE 22F46DM4 HELLO KITTY', $listedProductViews[1]->getName());
    }

    public function testGetForTop(): void
    {
        /** @var \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewRepository $listedProductViewRepository */
        $listedProductViewRepository = $this->getContainer()->get(ListedProductViewRepository::class);

        $listedProductViews = $listedProductViewRepository->getForTop(1);

        $this->assertCount(1, $listedProductViews);

        $this->assertArrayHasKey(1, $listedProductViews);

        $this->assertInstanceOf(ListedProductViewInterface::class, $listedProductViews[1]);

        $this->assertEquals('22" Sencor SLE 22F46DM4 HELLO KITTY', $listedProductViews[1]->getName());
    }

    public function testGetPaginatedForFilteredInCategory(): void
    {
        /** @var \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewRepository $listedProductViewRepository */
        $listedProductViewRepository = $this->getContainer()->get(ListedProductViewRepository::class);
        $emptyFilterData = new ProductFilterData();

        $paginationResults = $listedProductViewRepository->getPaginatedForFilteredInCategory(9, $emptyFilterData, ProductListOrderingConfig::ORDER_BY_NAME_ASC, 1, 5);
        $listedProductViews = $paginationResults->getResults();

        $this->assertCount(5, $listedProductViews);

        $this->assertArrayHasKey(72, $listedProductViews);

        $this->assertInstanceOf(ListedProductViewInterface::class, $listedProductViews[72]);

        $this->assertEquals('100 Czech crowns ticket', $listedProductViews[72]->getName());
    }
}
