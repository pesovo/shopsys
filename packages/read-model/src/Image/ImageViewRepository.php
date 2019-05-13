<?php

declare(strict_types=1);

namespace Shopsys\ReadModelBundle\Image;

use Shopsys\FrameworkBundle\Component\Image\ImageFacade;

/**
 * @experimental
 */
class ImageViewRepository
{
    /**
     * @var \Shopsys\FrameworkBundle\Component\Image\ImageFacade
     */
    protected $imageFacade;

    /**
     * @var \Shopsys\ReadModelBundle\Image\ImageViewFactory
     */
    protected $imageViewFactory;

    /**
     * @param \Shopsys\FrameworkBundle\Component\Image\ImageFacade $imageFacade
     * @param \Shopsys\ReadModelBundle\Image\ImageViewFactory $imageViewFactory
     */
    public function __construct(ImageFacade $imageFacade, ImageViewFactory $imageViewFactory)
    {
        $this->imageFacade = $imageFacade;
        $this->imageViewFactory = $imageViewFactory;
    }

    /**
     * @param string $entityClass
     * @param array $entityIds
     * @return array
     */
    public function getForEntityIds(string $entityClass, array $entityIds): array
    {
        $imagesIndexedByEntityIds = $this->imageFacade->getImagesOrNullsByEntitiesIndexedByEntityId($entityIds, $entityClass);

        $imageViewsOrNullsIndexedByEntityIds = [];
        foreach ($entityIds as $entityId) {
            if (!isset($imagesIndexedByEntityIds[$entityId])) {
                $imageViewsOrNullsIndexedByEntityIds[$entityId] = null;
            } else {
                $imageViewsOrNullsIndexedByEntityIds[$entityId] = $this->imageViewFactory->createFromImage(
                    $imagesIndexedByEntityIds[$entityId]
                );
            }
        }

        return $imageViewsOrNullsIndexedByEntityIds;
    }
}
