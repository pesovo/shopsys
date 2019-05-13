<?php

declare(strict_types=1);

namespace Shopsys\ReadModelBundle\Product\Listed;

use Shopsys\FrameworkBundle\Component\Paginator\PaginationResult;
use Shopsys\FrameworkBundle\Model\Product\Filter\ProductFilterData;

/**
 * @experimental
 */
class ListedProductViewFacade implements ListedProductViewFacadeInterface
{
    /**
     * @var \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewRepository
     */
    protected $listedProductViewRepository;

    /**
     * @param \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewRepository $listedProductViewRepository
     */
    public function __construct(
        ListedProductViewRepository $listedProductViewRepository
    ) {
        $this->listedProductViewRepository = $listedProductViewRepository;
    }

    /**
     * @param int|null $limit Returns all products when "null" is provided
     * @return \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewInterface[]
     */
    public function getTop(?int $limit = null): array
    {
        return $this->listedProductViewRepository->getForTop($limit);
    }

    /**
     * @param int $productId
     * @param int|null $limit Returns all products when "null" is provided
     * @return \Shopsys\ReadModelBundle\Product\Listed\ListedProductView[]
     */
    public function getAccessories(int $productId, ?int $limit = null): array
    {
        return $this->listedProductViewRepository->getForAccessories($productId, $limit);
    }

    /**
     * @param int $categoryId
     * @param \Shopsys\FrameworkBundle\Model\Product\Filter\ProductFilterData $filterData
     * @param string $orderingModeId {@see Shopsys\FrameworkBundle\Model\Product\Listing\ProductListOrderingConfig}
     * @param int $page Page number (starting with 1)
     * @param int $limit Number of products per page (must be greater than 0)
     * @return \Shopsys\FrameworkBundle\Component\Paginator\PaginationResult
     */
    public function getFilteredPaginatedInCategory(int $categoryId, ProductFilterData $filterData, string $orderingModeId, int $page, int $limit): PaginationResult
    {
        return $this->listedProductViewRepository->getPaginatedForFilteredInCategory(
            $categoryId,
            $filterData,
            $orderingModeId,
            $page,
            $limit
        );
    }

    /**
     * @param string|null $searchText
     * @param \Shopsys\FrameworkBundle\Model\Product\Filter\ProductFilterData $filterData
     * @param string $orderingModeId {@see Shopsys\FrameworkBundle\Model\Product\Listing\ProductListOrderingConfig}
     * @param int $page Page number (starting with 1)
     * @param int $limit Number of products per page (must be greater than 0)
     * @return \Shopsys\FrameworkBundle\Component\Paginator\PaginationResult
     */
    public function getFilteredPaginatedForSearch(?string $searchText, ProductFilterData $filterData, string $orderingModeId, int $page, int $limit): PaginationResult
    {
        return $this->listedProductViewRepository->getPaginatedForFilteredSearch(
            $searchText,
            $filterData,
            $orderingModeId,
            $page,
            $limit
        );
    }

    /**
     * @param int $brandId
     * @param string $orderingModeId {@see Shopsys\FrameworkBundle\Model\Product\Listing\ProductListOrderingConfig}
     * @param int $page Page number (starting with 1)
     * @param int $limit Number of products per page (must be greater than 0)
     * @return \Shopsys\FrameworkBundle\Component\Paginator\PaginationResult
     */
    public function getPaginatedForBrand(int $brandId, string $orderingModeId, int $page, int $limit): PaginationResult
    {
        return $this->listedProductViewRepository->getPaginatedForBrand($brandId, $orderingModeId, $page, $limit);
    }
}
