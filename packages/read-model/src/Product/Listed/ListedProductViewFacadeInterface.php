<?php

namespace Shopsys\ReadModelBundle\Product\Listed;

use Shopsys\FrameworkBundle\Component\Paginator\PaginationResult;
use Shopsys\FrameworkBundle\Model\Product\Filter\ProductFilterData;

interface ListedProductViewFacadeInterface
{
    /**
     * @param int|null $limit Returns all products when "null" is provided
     * @return \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewInterface[]
     */
    public function getTop(?int $limit = null): array;

    /**
     * @param int $productId
     * @param int|null $limit Returns all products when "null" is provided
     * @return \Shopsys\ReadModelBundle\Product\Listed\ListedProductView[]
     */
    public function getAccessories(int $productId, ?int $limit = null): array;

    /**
     * @param int $categoryId
     * @param \Shopsys\FrameworkBundle\Model\Product\Filter\ProductFilterData $filterData
     * @param string $orderingModeId {@see Shopsys\FrameworkBundle\Model\Product\Listing\ProductListOrderingConfig}
     * @param int $page Page number (starting with 1)
     * @param int $limit Number of products per page (must be greater than 0)
     * @return \Shopsys\FrameworkBundle\Component\Paginator\PaginationResult
     */
    public function getFilteredPaginatedInCategory(int $categoryId, ProductFilterData $filterData, string $orderingModeId, int $page, int $limit): PaginationResult;

    /**
     * @param string|null $searchText
     * @param \Shopsys\FrameworkBundle\Model\Product\Filter\ProductFilterData $filterData
     * @param string $orderingModeId {@see Shopsys\FrameworkBundle\Model\Product\Listing\ProductListOrderingConfig}
     * @param int $page Page number (starting with 1)
     * @param int $limit Number of products per page (must be greater than 0)
     * @return \Shopsys\FrameworkBundle\Component\Paginator\PaginationResult
     */
    public function getFilteredPaginatedForSearch(?string $searchText, ProductFilterData $filterData, string $orderingModeId, int $page, int $limit): PaginationResult;

    /**
     * @param int $brandId
     * @param string $orderingModeId {@see Shopsys\FrameworkBundle\Model\Product\Listing\ProductListOrderingConfig}
     * @param int $page Page number (starting with 1)
     * @param int $limit Number of products per page (must be greater than 0)
     * @return \Shopsys\FrameworkBundle\Component\Paginator\PaginationResult
     */
    public function getPaginatedForBrand(int $brandId, string $orderingModeId, int $page, int $limit): PaginationResult;
}
