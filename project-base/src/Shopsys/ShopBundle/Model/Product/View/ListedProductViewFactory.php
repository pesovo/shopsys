<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Product\View;

use Shopsys\FrameworkBundle\Model\Product\Product;
use Shopsys\ReadModelBundle\Image\ImageView;
use Shopsys\ReadModelBundle\Product\Action\ProductActionView;
use Shopsys\ReadModelBundle\Product\Listed\ListedProductView as BaseListedProductView;
use Shopsys\ReadModelBundle\Product\Listed\ListedProductViewFactory as BaseListedProductViewFactory;

class ListedProductViewFactory extends BaseListedProductViewFactory
{
    /**
     * @param \Shopsys\ShopBundle\Model\Product\Product $product
     * @param \Shopsys\ReadModelBundle\Image\ImageView|null $imageView
     * @param \Shopsys\ReadModelBundle\Product\Action\ProductActionView $productActionView
     * @return \Shopsys\ReadModelBundle\Product\Listed\ListedProductView
     */
    public function createFromProduct(Product $product, ?ImageView $imageView, ProductActionView $productActionView): BaseListedProductView
    {
        return new ListedProductView(
            $product->getId(),
            $product->getName(),
            $product->getShortDescription($this->domain->getId()),
            $product->getCalculatedAvailability()->getName(),
            $this->productCachedAttributesFacade->getProductSellingPrice($product),
            $this->getFlagIdsForProduct($product),
            $productActionView,
            $imageView,
            $product->getText2() ? $product->getText2() : null
        );
    }
}
