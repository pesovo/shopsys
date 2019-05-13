<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Product\View;

use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice;
use Shopsys\ReadModelBundle\Image\ImageViewInterface;
use Shopsys\ReadModelBundle\Product\Action\ProductActionViewInterface;
use Shopsys\ReadModelBundle\Product\Listed\ListedProductView as BaseListedProductView;

class ListedProductView extends BaseListedProductView
{
    /**
     * @var string|null
     */
    protected $brandName;

    /**
     * @param int $id
     * @param string $name
     * @param string|null $brandName
     * @param string|null $shortDescription
     * @param string $availability
     * @param \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice $sellingPrice
     * @param array $flagIds
     * @param \Shopsys\ReadModelBundle\Product\Action\ProductActionViewInterface $action
     * @param \Shopsys\ReadModelBundle\Image\ImageViewInterface|null $image
     */
    public function __construct(
        int $id,
        string $name,
        ?string $brandName,
        ?string $shortDescription,
        string $availability,
        ProductPrice $sellingPrice,
        array $flagIds,
        ProductActionViewInterface $action,
        ?ImageViewInterface $image
    ) {
        parent::__construct($id, $name, $shortDescription, $availability, $sellingPrice, $flagIds, $action, $image);

        $this->brandName = $brandName;
    }

    /**
     * @return string|null
     */
    public function getBrandName(): ?string
    {
        return $this->brandName;
    }
}
