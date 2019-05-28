<?php

declare(strict_types=1);

namespace Shopsys\ApiBundle\Controller\V1;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Shopsys\ApiBundle\Component\HeaderLinks\HeaderLinksTransformer;
use Shopsys\FrameworkBundle\Model\Product\Product;
use Shopsys\FrameworkBundle\Model\Product\ProductFacade;
use Shopsys\FrameworkBundle\Model\Product\ProductQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @experimental
 */
class ProductController extends AbstractFOSRestController
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\ProductFacade
     */
    protected $productFacade;

    /**
     * @var \Shopsys\ApiBundle\Controller\V1\ApiProductTransformer
     */
    protected $productTransformer;

    /**
     * @var \Shopsys\ApiBundle\Component\HeaderLinks\HeaderLinksTransformer
     */
    protected $linksTransformer;

    /**
     * @var int
     */
    protected $pageSize = 100;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductFacade $productFacade
     * @param \Shopsys\ApiBundle\Controller\V1\ApiProductTransformer $productTransformer
     * @param \Shopsys\ApiBundle\Component\HeaderLinks\HeaderLinksTransformer $linksTransformer
     */
    public function __construct(ProductFacade $productFacade, ApiProductTransformer $productTransformer, HeaderLinksTransformer $linksTransformer)
    {
        $this->productFacade = $productFacade;
        $this->productTransformer = $productTransformer;
        $this->linksTransformer = $linksTransformer;
    }

    /**
     * Retrieves an Product resource
     * @Get("/products/{uuid}")
     * @param string $uuid
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProductAction(string $uuid): Response
    {
        $this->validateUuid($uuid);
        $product = $this->productFacade->getByUuid($uuid);
        $productArray = $this->productTransformer->transform($product);

        return $this->handleView(View::create($productArray, 200));
    }

    /**
     * Retrieves an multiple Product resources
     * @Get("/products")
     * @QueryParam(name="page", requirements="\d+", default=1)
     * @QueryParam(name="uuids", map=true, requirements="[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}", allowBlank=false)
     * @param \FOS\RestBundle\Request\ParamFetcher $paramFetcher
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProductsAction(ParamFetcher $paramFetcher, Request $request): Response
    {
        $page = (int)$paramFetcher->get('page');

        $query = new ProductQuery($this->pageSize, $page);

        $filterUuids = $paramFetcher->get('uuids');
        if (is_array($filterUuids)) {
            foreach ($filterUuids as $uuid) {
                $this->validateUuid($uuid);
            }
            $query = $query->withUuids($filterUuids);
        }

        $productsResult = $this->productFacade->findByQuery($query);

        $productsArray = array_map(function (Product $product) {
            return $this->productTransformer->transform($product);
        }, $productsResult->getResults());

        $links = $this->linksTransformer->fromPaginationResult($productsResult, $request->getUri());

        $view = View::create($productsArray, 200, ['Link' => $links->format()]);

        return $this->handleView($view);
    }

    /**
     * @param string $uuid
     */
    protected function validateUuid(string $uuid): void
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidArgumentException('Input UUID is not valid');
        }
    }
}
