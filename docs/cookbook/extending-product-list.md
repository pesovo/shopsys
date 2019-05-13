# Extending Product List

Frontend product lists leverage [the read model concept](/docs/model/introduction-to-read-model.md), i.e. special view objects are used instead of common Doctrine entities in the Twig templates.

This cookbook describes how to extend the frontend product lists with step by step instructions on two distinct scenarios.

## Scenario 1 - Display a brand name in a product view on the product list

### 1. Extend `ListedProductView` class and add a new attribute.

The class encapsulates the data that are needed for displaying products on FE product lists.
We want to display a brand name for each product so we need to add the attribute to the class.

```php
declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Product\View;

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
```

### 2. Extend `ListedProductViewFactory` so it returns the new required data

The class is responsible for retrieving the data that are needed for rendering the product lists. We need to include the brand name into the data which can be achieved by overriding a single method - `createFromProduct()`.

```php
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
    protected function createFromProduct(Product $product, ?ImageViewInterface $imageView, ProductActionViewInterface $productActionView): ListedProductViewInterface
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
```

You need to register your new class as an alias for the one from the framework core in `services.yml`:

```yml
Shopsys\ReadModelBundle\Product\Listed\ListedProductViewFactory: '@Shopsys\ShopBundle\Model\Product\View\ListedProductViewFactory'
```

### 3. Modify the frontend template for rendering product lists so it displays the new attribute

```diff
{# src/Shopsys/ShopBundle/Resources/views/Front/Content/Product/productListMacro.html.twig #}
```
