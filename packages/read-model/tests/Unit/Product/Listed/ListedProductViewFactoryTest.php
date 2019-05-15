<?php

namespace Tests\ReadModelBundle\Unit\Product\Listed;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Pricing\Price;
use Shopsys\FrameworkBundle\Model\Product\Availability\Availability;
use Shopsys\FrameworkBundle\Model\Product\Flag\Flag;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice;
use Shopsys\FrameworkBundle\Model\Product\Product;
use Shopsys\FrameworkBundle\Model\Product\ProductCachedAttributesFacade;
use Shopsys\ReadModelBundle\Image\ImageView;
use Shopsys\ReadModelBundle\Image\ImageViewRepository;
use Shopsys\ReadModelBundle\Product\Action\ProductActionView;
use Shopsys\ReadModelBundle\Product\Action\ProductActionViewRepository;
use Shopsys\ReadModelBundle\Product\Listed\ListedProductViewFactory;

class ListedProductViewFactoryTest extends TestCase
{
    public function testCreateFromProducts(): void
    {
        $productName1 = '22" Sencor SLE 22F46DM4 HELLO KITTY';
        $productName2 = '32" Philips 32PFL4308';
        $priceAmount = 10;

        $imageViewRepositoryMock = $this->createImageViewRepositoryMock();
        $productActionViewRepositoryMock = $this->createProductActionViewRepositoryMock();
        $domainMock = $this->createDomainMock();
        $productCachedAttributesFacadeMock = $this->createProductCachedAttributesFacadeMock($priceAmount);

        $listedProductViewFactory = new ListedProductViewFactory($imageViewRepositoryMock, $productActionViewRepositoryMock, $domainMock, $productCachedAttributesFacadeMock);

        $productsMock = [
            $this->createProductMock(
                1,
                $productName1,
                [
                    $this->createFlagMock(1),
                    $this->createFlagMock(5),
                ]
            ),
            $this->createProductMock(
                2,
                $productName2,
                new ArrayCollection([
                    $this->createFlagMock(4),
                    $this->createFlagMock(3),
                ])
            ),
        ];

        /** @var \Shopsys\ReadModelBundle\Product\Listed\ListedProductView[] $listedProductViews */
        $listedProductViews = $listedProductViewFactory->createFromProducts($productsMock);

        $this->assertEquals(1, $listedProductViews[1]->getId());
        $this->assertEquals(2, $listedProductViews[2]->getId());

        $this->assertEquals($productName1, $listedProductViews[1]->getName());
        $this->assertEquals($productName2, $listedProductViews[2]->getName());

        $this->assertEquals([1, 5], $listedProductViews[1]->getFlagIds());
        $this->assertEquals([4, 3], $listedProductViews[2]->getFlagIds());

        $this->assertEquals($this->createProductPrice($priceAmount), $listedProductViews[1]->getSellingPrice());
        $this->assertEquals($this->createProductPrice($priceAmount), $listedProductViews[2]->getSellingPrice());
    }

    /**
     * @param int $id
     * @param string $name
     * @param array|\Doctrine\Common\Collections\ArrayCollection $flags
     * @return \PHPUnit\Framework\MockObject\MockObject|\Shopsys\FrameworkBundle\Model\Product\Product
     */
    private function createProductMock(int $id, string $name, $flags)
    {
        $productMock = $this->createMock(Product::class);

        $productMock->method('getId')->willReturn($id);
        $productMock->method('getName')->willReturn($name);
        $productMock->method('getShortDescription')->willReturn('short description');
        $productMock->method('getFlags')->willReturn($flags);

        $productAvailabilityMock = $this->createMock(Availability::class);
        $productAvailabilityMock->method('getName')->willReturn('available');

        $productMock->method('getCalculatedAvailability')->willReturn($productAvailabilityMock);

        return $productMock;
    }

    /**
     * @param int $id
     */
    private function createFlagMock(int $id)
    {
        $flagMock = $this->createMock(Flag::class);
        $flagMock->method('getId')->willReturn($id);

        return $flagMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Shopsys\ReadModelBundle\Image\ImageViewRepository
     */
    private function createImageViewRepositoryMock()
    {
        $imageViewRepositoryMock = $this->createMock(ImageViewRepository::class);
        $imageViewRepositoryMock->method('getForEntityIds')->willReturn([
            1 => new ImageView(1, 'jpg', 'product', null),
            2 => new ImageView(2, 'jpg', 'product', null),
        ]);
        return $imageViewRepositoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Shopsys\ReadModelBundle\Product\Action\ProductActionViewRepository
     */
    private function createProductActionViewRepositoryMock()
    {
        $productActionViewRepositoryMock = $this->createMock(ProductActionViewRepository::class);
        $productActionViewRepositoryMock->method('getForProducts')->willReturn([
            1 => new ProductActionView(1, false, false, 'http://webserver:8080/product/1'),
            2 => new ProductActionView(2, false, false, 'http://webserver:8080/product/2'),
        ]);
        return $productActionViewRepositoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    private function createDomainMock()
    {
        $domainMock = $this->createMock(Domain::class);
        $domainMock->method('getId')->willReturn(1);
        return $domainMock;
    }

    /**
     * @param int $priceAmount
     * @return \PHPUnit\Framework\MockObject\MockObject|\Shopsys\FrameworkBundle\Model\Product\ProductCachedAttributesFacade
     */
    private function createProductCachedAttributesFacadeMock(int $priceAmount)
    {
        $productCachedAttributesFacadeMock = $this->createMock(ProductCachedAttributesFacade::class);
        $productCachedAttributesFacadeMock->method('getProductSellingPrice')->willReturn(
            $this->createProductPrice($priceAmount)
        );
        return $productCachedAttributesFacadeMock;
    }

    /**
     * @param int $amount
     * @return \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice
     */
    private function createProductPrice(int $amount): ProductPrice
    {
        return new ProductPrice(new Price(Money::create($amount), Money::create($amount)), false);
    }
}
