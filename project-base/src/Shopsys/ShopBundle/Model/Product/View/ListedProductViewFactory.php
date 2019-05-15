<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Product\View;

use Shopsys\FrameworkBundle\Model\Product\Product;
use Shopsys\ReadModelBundle\Image\ImageViewInterface;
use Shopsys\ReadModelBundle\Product\Action\ProductActionViewInterface;
use Shopsys\ReadModelBundle\Product\Listed\ListedProductViewFactory as BaseListedProductViewFactory;
use Shopsys\ReadModelBundle\Product\Listed\ListedProductViewInterface;

class ListedProductViewFactory extends BaseListedProductViewFactory
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Product $product
     * @param \Shopsys\ReadModelBundle\Image\ImageViewInterface|null $imageView
     * @param \Shopsys\ReadModelBundle\Product\Action\ProductActionViewInterface $productActionView
     * @return \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewInterface
     */
    public function createFromProduct(Product $product, ?ImageViewInterface $imageView, ProductActionViewInterface $productActionView): ListedProductViewInterface
    {
        return new ListedProductView(
            $product->getId(),
            $product->getName(),
            $product->getBrand() ? $product->getBrand()->getName() : null,
            $product->getShortDescription($this->domain->getId()),
            $product->getCalculatedAvailability()->getName(),
            $this->productCachedAttributesFacade->getProductSellingPrice($product),
            $this->getFlagIdsForProduct($product),
            $productActionView,
            $imageView
        );
    }
}
