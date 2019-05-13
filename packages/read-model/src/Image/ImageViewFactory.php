<?php

declare(strict_types=1);

namespace Shopsys\ReadModelBundle\Image;

use Shopsys\FrameworkBundle\Component\Image\Image;

/**
 * @experimental
 */
class ImageViewFactory
{
    /**
     * @param \Shopsys\FrameworkBundle\Component\Image\Image $image
     * @return \Shopsys\ReadModelBundle\Image\ImageViewInterface
     */
    public function createFromImage(Image $image): ImageViewInterface
    {
        return new ImageView(
            $image->getId(),
            $image->getExtension(),
            $image->getEntityName(),
            $image->getType()
        );
    }
}
