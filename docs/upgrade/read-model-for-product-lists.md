# Read Model for Product Lists

There is a new layer in Shopsys Framework, called [read model](/docs/model/introduction-to-read-model.md), separating the templates and application model.
Besides better logical separation of the application, it is a first step towards usage of Elasticsearch for frontend product lists, and hence significant performance boost in the near future.
The read model package is marked as *experimental* at the moment so there is a possibility we might introduce some BC breaking changes there. 
You do not need to perform the upgrade instantly, however, if you do so, you will be better prepared for the upcoming changes.

<!-- TODO change link to PR to the split merge commit in project-base -->
To start using the read model, follow the instructions (you can also find inspiration in [#1018](https://github.com/shopsys/shopsys/pull/1018) where the read model was introduced to `project-base`):
- add dependency on `shopsys/read-model` to your `composer.json`
- register the bundle in your `app/AppKernel.php`:
    ```php
    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = [
                // ...
                new Shopsys\ReadModelBundle\ShopsysReadModelBundle(),
                // ...
            ];
    
            // ...
    
            return $bundles;
        }
    
        // ...
    }
    ```
    - Note: the bundle needs to be registered after `ShopsysFrameworkBundle` as it overwrites its `image` Twig function
- in all frontend controllers where you are listing products, use the new `Shopsys\ReadModelBundle\Product\Listed\ListedProductViewFacadeInterface`:
    - in `ProductController::detailAction`:
        ```diff
        - $accessories = $this->productOnCurrentDomainFacade->getAccessoriesForProduct($product);
        + $accessories = $this->listedProductsFacade->getAllAccessories($product->getId());
        ```
    - in `ProductController::listByCategoryAction`:
        ```diff
        - $paginationResult = $this->productOnCurrentDomainFacade->getPaginatedProductsInCategory(
        -      $productFilterData,
        -      $orderingModeId,
        -      $page,
        -      self::PRODUCTS_PER_PAGE,
        -      $id
        -  );
        + $paginationResult = $this->listedProductsFacade->getFilteredPaginatedInCategory(
        +      $id,
        +      $productFilterData,
        +      $orderingModeId,
        +      $page,
        +      self::PRODUCTS_PER_PAGE
        +  );
        ```
    - in `ProductController::searchAction`:
        ```diff
        - $paginationResult = $this->productOnCurrentDomainFacade->getPaginatedProductsForSearch(
        + $paginationResult = $this->listedProductsFacade->getFilteredPaginatedForSearch(
        ```
    - in `ProductController::listByBrandAction`:
        ```diff
        - $paginationResult = $this->productOnCurrentDomainFacade->getPaginatedProductsForBrand(
        -     $orderingModeId,
        -     $page,
        -     self::PRODUCTS_PER_PAGE,
        -     $id
        - );
        + $paginationResult = $this->listedProductsFacade->getPaginatedForBrand(
        +     $id,
        +     $orderingModeId,
        +     $page,
        +     self::PRODUCTS_PER_PAGE
        + );
        ```
    - in `CartController::addProductAjaxAxction`:
        ```diff
        - $accessories = $this->productAccessoryFacade->getTopOfferedAccessories(
        -     $addProductResult->getCartItem()->getProduct(),
        -     $this->domain->getId(),
        -     $this->currentCustomer->getPricingGroup(),
        -     self::AFTER_ADD_WINDOW_ACCESSORIES_LIMIT
        );
        + $accessories = $this->listedProductsFacade->getAccessories(
        +     $addProductResult->getCartItem()->getProduct()->getId(),
        +     self::AFTER_ADD_WINDOW_ACCESSORIES_LIMIT
        + );
        ```
    - in `HomepageController::indexAction`:
        ```diff
        - $topProducts = $this->topProductFacade->getAllOfferedProducts(
        -     $this->domain->getId(),
        -     $this->currentCustomer->getPricingGroup()
        - );
        + $topProducts = $this->listedProductsFacade->getAllTop();
        ```
- edit your `productListMacro.html.twig` so it now works with instances of `ListedProductView` instead of `Product` entities
    - to render product flags by their ids, you need to implement new [`FlagsExtension`](/project-base/src/Shopsys/ShopBundle/Twig/FlagsExtension.php) with `renderFlagsByIds` function and add a new [`productFlags.html.twig`](/project-base/src/Shopsys/ShopBundle/Resources/views/Front/Inline/Product/productFlags.html.twig) template
    - to render "add to cart" form, add `CartController::productActionAction` and use it instead of `addProductFormAction` 