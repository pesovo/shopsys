<?php

namespace Tests\ShopBundle\Functional\Model\Product;

use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Product\Filter\ParameterFilterData;
use Shopsys\FrameworkBundle\Model\Product\Filter\ProductFilterData;
use Shopsys\FrameworkBundle\Model\Product\Listing\ProductListOrderingConfig;
use Shopsys\FrameworkBundle\Model\Product\Parameter\ParameterRepository;
use Shopsys\FrameworkBundle\Model\Product\Parameter\ParameterValue;
use Shopsys\FrameworkBundle\Model\Product\ProductOnCurrentDomainFacade;
use Shopsys\ShopBundle\DataFixtures\Demo\BrandDataFixture;
use Shopsys\ShopBundle\DataFixtures\Demo\CategoryDataFixture;
use Shopsys\ShopBundle\DataFixtures\Demo\FlagDataFixture;
use Shopsys\ShopBundle\Model\Category\Category;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class ProductOnCurrentDomainFacadeTest extends TransactionFunctionalTestCase
{
    public function testFilterByMinimalPrice()
    {
        $category = $this->getReference(CategoryDataFixture::CATEGORY_TV);

        $productFilterData = new ProductFilterData();
        $productFilterData->minimalPrice = Money::create(1000);
        $paginationResult = $this->getPaginationResultInCategory($productFilterData, $category);

        $this->assertCount(5, $paginationResult->getResults());
    }

    public function testFilterByMaximalPrice()
    {
        $category = $this->getReference(CategoryDataFixture::CATEGORY_TV);

        $productFilterData = new ProductFilterData();
        $productFilterData->maximalPrice = Money::create(10000);
        $paginationResult = $this->getPaginationResultInCategory($productFilterData, $category);

        $this->assertCount(8, $paginationResult->getResults());
    }

    public function testFilterByStockAvailability()
    {
        $category = $this->getReference(CategoryDataFixture::CATEGORY_PHONES);

        $productFilterData = new ProductFilterData();
        $productFilterData->inStock = true;
        $paginationResult = $this->getPaginationResultInCategory($productFilterData, $category);

        $this->assertCount(8, $paginationResult->getResults());
    }

    public function testFilterByFlag()
    {
        $category = $this->getReference(CategoryDataFixture::CATEGORY_PRINTERS);

        $flagTopProduct = $this->getReference(FlagDataFixture::FLAG_TOP_PRODUCT);
        $productFilterData = new ProductFilterData();
        $productFilterData->flags = [$flagTopProduct];
        $paginationResult = $this->getPaginationResultInCategory($productFilterData, $category);

        $this->assertCount(4, $paginationResult->getResults());
    }

    public function testFilterByFlagsReturnsProductsWithAnyOfUsedFlags()
    {
        $category = $this->getReference(CategoryDataFixture::CATEGORY_BOOKS);

        $flagTopProduct = $this->getReference(FlagDataFixture::FLAG_TOP_PRODUCT);
        $flagActionProduct = $this->getReference(FlagDataFixture::FLAG_ACTION_PRODUCT);
        $productFilterData = new ProductFilterData();
        $productFilterData->flags = [$flagTopProduct, $flagActionProduct];
        $paginationResult = $this->getPaginationResultInCategory($productFilterData, $category);

        $this->assertCount(15, $paginationResult->getResults());
    }

    public function testFilterByBrand()
    {
        $category = $this->getReference(CategoryDataFixture::CATEGORY_PRINTERS);

        $brandDefender = $this->getReference(BrandDataFixture::BRAND_DEFENDER);
        $brandGenius = $this->getReference(BrandDataFixture::BRAND_GENIUS);
        $productFilterData = new ProductFilterData();
        $productFilterData->brands = [$brandDefender, $brandGenius];
        $paginationResult = $this->getPaginationResultInCategory($productFilterData, $category);

        $this->assertCount(4, $paginationResult->getResults());
    }

    public function testFilterByBrandsReturnsProductsWithAnyOfUsedBrands()
    {
        $category = $this->getReference(CategoryDataFixture::CATEGORY_PRINTERS);

        $brandHp = $this->getReference(BrandDataFixture::BRAND_HP);
        $brandCanon = $this->getReference(BrandDataFixture::BRAND_CANON);
        $productFilterData = new ProductFilterData();
        $productFilterData->brands = [$brandCanon, $brandHp];
        $paginationResult = $this->getPaginationResultInCategory($productFilterData, $category);

        $this->assertCount(1, $paginationResult->getResults());
    }

    public function testFilterByParameter()
    {
        $category = $this->getReference(CategoryDataFixture::CATEGORY_PRINTERS);

        $parameterFilterData = $this->createParameterFilterData(
            ['en' => 'Weight'],
            [['en' => '9 kg']]
        );
        $productFilterData = new ProductFilterData();
        $productFilterData->parameters = [$parameterFilterData];

        $paginationResult = $this->getPaginationResultInCategory($productFilterData, $category);

        $this->assertCount(1, $paginationResult->getResults());
    }

    public function testFilterByParametersUsesOrWithinTheSameParameter()
    {
        $category = $this->getReference(CategoryDataFixture::CATEGORY_PRINTERS);

        $parameterFilterData = $this->createParameterFilterData(
            ['en' => 'Weight'],
            [
                ['en' => '9 kg'],
                ['en' => '2 kg'],
            ]
        );
        $productFilterData = new ProductFilterData();
        $productFilterData->parameters = [$parameterFilterData];
        $paginationResult = $this->getPaginationResultInCategory($productFilterData, $category);

        $this->assertCount(3, $paginationResult->getResults());
    }

    public function testFilterByParametersUsesAndWithinDistinctParameters()
    {
        $category = $this->getReference(CategoryDataFixture::CATEGORY_PRINTERS);

        $parameterFilterData1 = $this->createParameterFilterData(
            ['en' => 'Weight'],
            [['en' => '9 kg']]
        );
        $parameterFilterData2 = $this->createParameterFilterData(
            ['en' => 'Color'],
            [['en' => 'blue']]
        );
        $productFilterData = new ProductFilterData();
        $productFilterData->parameters = [$parameterFilterData1, $parameterFilterData2];
        $paginationResult = $this->getPaginationResultInCategory($productFilterData, $category);

        $this->assertCount(1, $paginationResult->getResults());
    }

    /**
     * @param array $namesByLocale
     * @param array $valuesTextsByLocales
     * @return \Shopsys\FrameworkBundle\Model\Product\Filter\ParameterFilterData
     */
    private function createParameterFilterData(array $namesByLocale, array $valuesTextsByLocales)
    {
        /** @var \Shopsys\FrameworkBundle\Model\Product\Parameter\ParameterRepository $parameterRepository */
        $parameterRepository = $this->getContainer()->get(ParameterRepository::class);

        $parameter = $parameterRepository->findParameterByNames($namesByLocale);
        $parameterValues = $this->getParameterValuesByLocalesAndTexts($valuesTextsByLocales);

        $parameterFilterData = new ParameterFilterData();
        $parameterFilterData->parameter = $parameter;
        $parameterFilterData->values = $parameterValues;

        return $parameterFilterData;
    }

    /**
     * @param array[] $valuesTextsByLocales
     * @return \Shopsys\FrameworkBundle\Model\Product\Parameter\ParameterValue[]
     */
    private function getParameterValuesByLocalesAndTexts(array $valuesTextsByLocales)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $parameterValues = [];

        foreach ($valuesTextsByLocales as $valueTextsByLocales) {
            foreach ($valueTextsByLocales as $locale => $text) {
                $parameterValues[] = $em->getRepository(ParameterValue::class)->findBy([
                    'text' => $text,
                    'locale' => $locale,
                ]);
            }
        }

        return $parameterValues;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Filter\ProductFilterData $productFilterData
     * @param \Shopsys\ShopBundle\Model\Category\Category $category
     * @return \Shopsys\FrameworkBundle\Component\Paginator\PaginationResult
     */
    private function getPaginationResultInCategory(ProductFilterData $productFilterData, Category $category)
    {
        /** @var \Shopsys\FrameworkBundle\Model\Product\ProductOnCurrentDomainFacade $productOnCurrentDomainFacade */
        $productOnCurrentDomainFacade = $this->getContainer()->get(ProductOnCurrentDomainFacade::class);
        $page = 1;
        $limit = PHP_INT_MAX;

        return $productOnCurrentDomainFacade->getPaginatedProductsInCategory(
            $productFilterData,
            ProductListOrderingConfig::ORDER_BY_NAME_ASC,
            $page,
            $limit,
            $category->getId()
        );
    }
}
