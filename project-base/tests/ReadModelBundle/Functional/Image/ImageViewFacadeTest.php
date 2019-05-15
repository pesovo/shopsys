<?php

declare(strict_types=1);

namespace Tests\ReadModelBundle\Functional\Image;

use Shopsys\FrameworkBundle\Model\Product\Product;
use Shopsys\ReadModelBundle\Image\ImageViewFacade;
use Shopsys\ReadModelBundle\Image\ImageViewInterface;
use Tests\ShopBundle\Test\FunctionalTestCase;

class ImageViewFacadeTest extends FunctionalTestCase
{
    public function testGetForSingleEntityId(): void
    {
        $testedProductId = 1;

        $imageViewFacade = $this->getContainer()->get(ImageViewFacade::class);

        $imageViews = $imageViewFacade->getForEntityIds(Product::class, [$testedProductId]);

        $this->assertCount(1, $imageViews);
        $this->assertInstanceOf(ImageViewInterface::class, $imageViews[$testedProductId]);

        /** @var \Shopsys\ReadModelBundle\Image\ImageView[] $imageViews */
        $this->assertSame('product', $imageViews[1]->getEntityName());
        $this->assertSame(1, $imageViews[1]->getId());
        $this->assertSame('jpg', $imageViews[1]->getExtension());
    }

    public function testGetForInvalidEntityId(): void
    {
        $testedProductId = 99999;

        $imageViewFacade = $this->getContainer()->get(ImageViewFacade::class);

        $imageViews = $imageViewFacade->getForEntityIds(Product::class, [$testedProductId]);

        $this->assertCount(1, $imageViews);
        $this->assertNull($imageViews[$testedProductId]);
    }

    public function testGetForEntityIds(): void
    {
        $imageViewFacade = $this->getContainer()->get(ImageViewFacade::class);

        $imageViews = $imageViewFacade->getForEntityIds(Product::class, [1, 2, 3]);

        $this->assertCount(3, $imageViews);
        $this->assertInstanceOf(ImageViewInterface::class, $imageViews[1]);
        $this->assertInstanceOf(ImageViewInterface::class, $imageViews[2]);
        $this->assertInstanceOf(ImageViewInterface::class, $imageViews[3]);
    }
}
