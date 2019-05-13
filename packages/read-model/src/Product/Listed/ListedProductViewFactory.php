<?php

declare(strict_types=1);

namespace Shopsys\ReadModelBundle\Product\Listed;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Product\Flag\Flag;
use Shopsys\FrameworkBundle\Model\Product\Product;
use Shopsys\FrameworkBundle\Model\Product\ProductCachedAttributesFacade;
use Shopsys\ReadModelBundle\Image\ImageViewInterface;
use Shopsys\ReadModelBundle\Image\ImageViewRepository;
use Shopsys\ReadModelBundle\Product\Action\ProductActionViewInterface;
use Shopsys\ReadModelBundle\Product\Action\ProductActionViewRepository;

/**
 * @experimental
 */
class ListedProductViewFactory
{
    /**
     * @var \Shopsys\ReadModelBundle\Image\ImageViewRepository
     */
    protected $imageViewRepository;

    /**
     * @var \Shopsys\ReadModelBundle\Product\Action\ProductActionViewRepository
     */
    protected $productActionViewRepository;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\ProductCachedAttributesFacade
     */
    protected $productCachedAttributesFacade;

    /**
     * @param \Shopsys\ReadModelBundle\Image\ImageViewRepository $imageViewRepository
     * @param \Shopsys\ReadModelBundle\Product\Action\ProductActionViewRepository $productActionViewRepository
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCachedAttributesFacade $productCachedAttributesFacade
     */
    public function __construct(
        ImageViewRepository $imageViewRepository,
        ProductActionViewRepository $productActionViewRepository,
        Domain $domain,
        ProductCachedAttributesFacade $productCachedAttributesFacade
    ) {
        $this->imageViewRepository = $imageViewRepository;
        $this->productActionViewRepository = $productActionViewRepository;
        $this->domain = $domain;
        $this->productCachedAttributesFacade = $productCachedAttributesFacade;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Product\Product[] $products
     * @return \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewInterface[]
     */
    public function createFromProducts(array $products): array
    {
        $imageViews = $this->imageViewRepository->getForEntityIds(Product::class, $this->getIdsForProducts($products));
        $productActionViews = $this->productActionViewRepository->getForProducts($products);

        $listedProductViews = [];
        foreach ($products as $product) {
            $productId = $product->getId();
            $listedProductViews[] = $this->createFromProduct($product, $imageViews[$productId], $productActionViews[$productId]);
        }

        return $listedProductViews;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Product $product
     * @param \Shopsys\ReadModelBundle\Image\ImageViewInterface|null $imageView
     * @param \Shopsys\ReadModelBundle\Product\Action\ProductActionViewInterface $productActionView
     * @return \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewInterface
     */
    protected function createFromProduct(Product $product, ?ImageViewInterface $imageView, ProductActionViewInterface $productActionView): ListedProductViewInterface
    {
        return new ListedProductView(
            $product->getId(),
            $product->getName(),
            $product->getShortDescription($this->domain->getId()),
            $product->getCalculatedAvailability()->getName(),
            $this->productCachedAttributesFacade->getProductSellingPrice($product),
            $this->getFlagIdsForProduct($product),
            $productActionView,
            $imageView
        );
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Product $product
     * @return int[]
     */
    protected function getFlagIdsForProduct(Product $product): array
    {
        return array_map(static function (Flag $flag): int {
            return $flag->getId();
        }, $product->getFlags()->toArray());
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Product\Product[] $products
     * @return int[]
     */
    protected function getIdsForProducts(array $products): array
    {
        return array_map(static function (Product $product): int {
            return $product->getId();
        }, $products);
    }
}
