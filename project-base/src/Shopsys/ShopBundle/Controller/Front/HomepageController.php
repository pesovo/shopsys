<?php

namespace Shopsys\ShopBundle\Controller\Front;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Seo\SeoSettingFacade;
use Shopsys\FrameworkBundle\Model\Slider\SliderItemFacade;
use Shopsys\ReadModelBundle\Product\Listed\ListedProductViewFacadeInterface;

class HomepageController extends FrontBaseController
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Seo\SeoSettingFacade
     */
    private $seoSettingFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Slider\SliderItemFacade
     */
    private $sliderItemFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    private $domain;

    /**
     * @var \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewFacadeInterface
     */
    private $listedProductsFacade;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Slider\SliderItemFacade $sliderItemFacade
     * @param \Shopsys\FrameworkBundle\Model\Seo\SeoSettingFacade $seoSettingFacade
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\ReadModelBundle\Product\Listed\ListedProductViewFacadeInterface $listedProductsFacade
     */
    public function __construct(
        SliderItemFacade $sliderItemFacade,
        SeoSettingFacade $seoSettingFacade,
        Domain $domain,
        ListedProductViewFacadeInterface $listedProductsFacade
    ) {
        $this->sliderItemFacade = $sliderItemFacade;
        $this->seoSettingFacade = $seoSettingFacade;
        $this->domain = $domain;
        $this->listedProductsFacade = $listedProductsFacade;
    }

    public function indexAction()
    {
        $sliderItems = $this->sliderItemFacade->getAllVisibleOnCurrentDomain();
        $topProducts = $this->listedProductsFacade->getAllTop();

        return $this->render('@ShopsysShop/Front/Content/Default/index.html.twig', [
            'sliderItems' => $sliderItems,
            'topProducts' => $topProducts,
            'title' => $this->seoSettingFacade->getTitleMainPage($this->domain->getId()),
            'metaDescription' => $this->seoSettingFacade->getDescriptionMainPage($this->domain->getId()),
        ]);
    }
}
