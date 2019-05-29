<?php

declare(strict_types=1);

namespace Shopsys\ReadModelBundle\Product\Action;

use Shopsys\FrameworkBundle\Model\Product\Product;

/**
 * @experimental
 */
class ProductActionViewFactory
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Product $product
     * @param string $absoluteUrl
     * @return \Shopsys\ReadModelBundle\Product\Action\ProductActionView
     */
    public function createFromProduct(Product $product, string $absoluteUrl): ProductActionView
    {
        return new ProductActionView(
            $product->getId(),
            $product->isSellingDenied(),
            $product->isMainVariant(),
            $absoluteUrl
        );
    }

    /**
     * @param array $hit
     * @return \Shopsys\ReadModelBundle\Product\Action\ProductActionView
     */
    public function createFromHit(array $hit): ProductActionView
    {
        return new ProductActionView(
            (int)$hit['_id'],
            $hit['_source']['selling_denied'],
            $hit['_source']['main_variant'],
            $hit['_source']['detail_url']
        );
    }
}
