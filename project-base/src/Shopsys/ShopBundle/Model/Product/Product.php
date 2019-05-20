<?php

namespace Shopsys\ShopBundle\Model\Product;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceRecalculationScheduler;
use Shopsys\FrameworkBundle\Model\Product\Product as BaseProduct;
use Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactoryInterface;
use Shopsys\FrameworkBundle\Model\Product\ProductData as BaseProductData;

/**
 * @ORM\Table(name="products")
 * @ORM\Entity
 */
class Product extends BaseProduct
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text2;

    /**
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactoryInterface $productCategoryDomainFactory
     * @param \Shopsys\ShopBundle\Model\Product\Product[]|null $variants
     */
    protected function __construct(BaseProductData $productData, ProductCategoryDomainFactoryInterface $productCategoryDomainFactory, array $variants = null)
    {
        parent::__construct($productData, $productCategoryDomainFactory, $variants);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactoryInterface $productCategoryDomainFactory
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     * @param \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceRecalculationScheduler $productPriceRecalculationScheduler
     */
    public function edit(
        ProductCategoryDomainFactoryInterface $productCategoryDomainFactory,
        BaseProductData $productData,
        ProductPriceRecalculationScheduler $productPriceRecalculationScheduler
    ) {
        parent::edit($productCategoryDomainFactory, $productData, $productPriceRecalculationScheduler);
    }

    /**
     * @return string|null
     */
    public function getText2(): ?string
    {
        return $this->text2;
    }

    /**
     * @param string|null $text2
     */
    public function setText2(?string $text2): void
    {
        $this->text2 = $text2;
    }
}
