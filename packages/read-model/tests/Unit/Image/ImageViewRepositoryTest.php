<?php

declare(strict_types=1);

namespace Tests\ReadModelBundle\Unit\Image;

use PHPUnit\Framework\TestCase;
use Shopsys\FrameworkBundle\Component\Image\Image;
use Shopsys\FrameworkBundle\Component\Image\ImageFacade;
use Shopsys\ReadModelBundle\Image\ImageView;
use Shopsys\ReadModelBundle\Image\ImageViewFactory;
use Shopsys\ReadModelBundle\Image\ImageViewRepository;

class ImageViewRepositoryTest extends TestCase
{
    private const IMAGE_EXTENSION = 'jpg';

    /** @var \Shopsys\FrameworkBundle\Component\Image\ImageFacade */
    private $imageFacadeMock;

    protected function setUp()
    {
        parent::setUp();

        $this->imageFacadeMock = $this->createMock(ImageFacade::class);
        $this->imageFacadeMock->method('getImagesOrNullsByEntitiesIndexedByEntityId')
            ->willReturnCallback(function ($entityIds, string $entityClass) {
                $images = [];

                foreach ($entityIds as $entityId) {
                    // id 800 represents not existing product
                    if ($entityId === 800) {
                        continue;
                    }

                    $images[$entityId] = $this->createImageMock($entityId, $entityClass);
                }

                return $images;
            });
    }

    public function testGetForEntityIds(): void
    {
        $imageFactory = new ImageViewFactory();
        $imageViewRepository = new ImageViewRepository($this->imageFacadeMock, $imageFactory);

        $imageViews = $imageViewRepository->getForEntityIds('product', [1, 3, 5]);

        $this->assertEquals(new ImageView(1, self::IMAGE_EXTENSION, 'product', null), $imageViews[1]);
        $this->assertEquals(new ImageView(3, self::IMAGE_EXTENSION, 'product', null), $imageViews[3]);
        $this->assertEquals(new ImageView(5, self::IMAGE_EXTENSION, 'product', null), $imageViews[5]);
    }

    public function testGetForEntityIdsWithNullImages(): void
    {
        $imageFactory = new ImageViewFactory();
        $imageViewRepository = new ImageViewRepository($this->imageFacadeMock, $imageFactory);

        $imageViews = $imageViewRepository->getForEntityIds('product', [10, 800, 2]);

        $this->assertEquals(new ImageView(10, self::IMAGE_EXTENSION, 'product', null), $imageViews[10]);
        $this->assertNull($imageViews[800]);
        $this->assertEquals(new ImageView(2, self::IMAGE_EXTENSION, 'product', null), $imageViews[2]);
    }

    /**
     * @param int $id
     * @param string $entityClass
     * @return \Shopsys\FrameworkBundle\Component\Image\Image
     */
    private function createImageMock(int $id, string $entityClass): Image
    {
        $imageMock = $this->createMock(Image::class);

        $imageMock->method('getId')->willReturn($id);
        $imageMock->method('getExtension')->willReturn(self::IMAGE_EXTENSION);
        $imageMock->method('getEntityName')->willReturn($entityClass);
        $imageMock->method('getType')->willReturn(null);

        return $imageMock;
    }
}
