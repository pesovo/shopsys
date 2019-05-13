<?php

declare(strict_types=1);

namespace Shopsys\ReadModelBundle\Product\Listed;

use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice;
use Shopsys\ReadModelBundle\Image\ImageViewInterface;
use Shopsys\ReadModelBundle\Product\Action\ProductActionView;
use Shopsys\ReadModelBundle\Product\Action\ProductActionViewInterface;

/**
 * @experimental
 *
 * Class representing products in lists in FE templates (to avoid usage of Doctrine entities a hence achieve performance gain)
 */
class ListedProductView implements ListedProductViewInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Shopsys\ReadModelBundle\Image\ImageViewInterface|null
     */
    protected $image;

    /**
     * @var string
     */
    protected $availability;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice
     */
    protected $sellingPrice;

    /**
     * @var string|null
     */
    protected $shortDescription;

    /**
     * @var int[]
     */
    protected $flagIds = [];

    /**
     * @var \Shopsys\ReadModelBundle\Product\Action\ProductActionView
     */
    protected $action;

    /**
     * ListedProductView constructor.
     * @param int $id
     * @param string $name
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
        ?string $shortDescription,
        string $availability,
        ProductPrice $sellingPrice,
        array $flagIds,
        ProductActionViewInterface $action,
        ?ImageViewInterface $image
    ) {
        foreach ($flagIds as $flagId) {
            if (!is_int($flagId)) {
                throw new \InvalidArgumentException('"$flagIds" has to be an array of integers.');
            }
        }

        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->availability = $availability;
        $this->sellingPrice = $sellingPrice;
        $this->shortDescription = $shortDescription;
        $this->flagIds = $flagIds;
        $this->action = $action;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \Shopsys\ReadModelBundle\Image\ImageViewInterface|null
     */
    public function getImage(): ?ImageViewInterface
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getAvailability(): string
    {
        return $this->availability;
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice
     */
    public function getSellingPrice(): ProductPrice
    {
        return $this->sellingPrice;
    }

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * @return int[]
     */
    public function getFlagIds(): array
    {
        return $this->flagIds;
    }

    /**
     * @return \Shopsys\ReadModelBundle\Product\Action\ProductActionView
     */
    public function getAction(): ProductActionView
    {
        return $this->action;
    }
}
