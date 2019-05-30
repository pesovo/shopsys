<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Shopsys\ApiBundle\Component\HeaderLinks\HeaderLinksTransformer;
use Shopsys\ApiBundle\Controller\V1\ApiProductTransformer;
use Shopsys\ApiBundle\Controller\V1\ProductController;
use Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Setting\Setting;
use Shopsys\FrameworkBundle\Model\Product\ProductFacade;

class ProductControllerTest extends TestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\ProductFacade|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productFacade;

    /**
     * @var \Shopsys\ApiBundle\Controller\V1\ProductController
     */
    protected $productController;

    protected function setUp()
    {
        $productTransformer = new ApiProductTransformer($this->createDomain());
        $this->productFacade = $this->createMock(ProductFacade::class);
        $linksTransformer = new HeaderLinksTransformer();

        $this->productController = new ProductController($this->productFacade, $productTransformer, $linksTransformer);
    }

    public function testGetProductActionWithUuidIncludingInvalidCharacter()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->productController->getProductAction('09be9850-9a3a-443f-b993-4c1230467b3x');
    }

    /**
     * @return \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected function createDomain(): Domain
    {
        return new Domain(
            [new DomainConfig(1, 'http://example.com/', 'czech', 'cs')],
            $this->createMock(Setting::class)
        );
    }
}
