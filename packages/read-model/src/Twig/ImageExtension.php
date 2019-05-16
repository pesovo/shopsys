<?php

declare(strict_types=1);

namespace Shopsys\ReadModelBundle\Twig;

use Shopsys\FrameworkBundle\Twig\ImageExtension as BaseImageExtension;
use Shopsys\ReadModelBundle\Image\ImageViewInterface;

class ImageExtension extends BaseImageExtension
{
    /**
     * @param \Shopsys\FrameworkBundle\Component\Image\Image|\Shopsys\ReadModelBundle\Image\ImageViewInterface|Object|null $imageView
     * @param array $attributes
     * @return string
     */
    public function getImageHtml($imageView, array $attributes = []): string
    {
        if ($imageView === null) {
            return $this->getNoimageHtml($attributes);
        }

        if ($imageView instanceof ImageViewInterface) {
            $this->preventDefault($attributes);

            $entityName = $imageView->getEntityName();

            $attributes['src'] = $this->imageFacade->getImageUrlFromAttributes(
                $this->domain->getCurrentDomainConfig(),
                $imageView->getId(),
                $imageView->getExtension(),
                $entityName,
                $imageView->getType(),
                $attributes['size']
            );

            $additionalImagesData = $this->imageFacade->getAdditionalImagesDataFromAttributes(
                $this->domain->getCurrentDomainConfig(),
                $imageView->getId(),
                $imageView->getExtension(),
                $entityName,
                $imageView->getType(),
                $attributes['size']
            );

            return $this->getImageHtmlByEntityName($attributes, $entityName, $additionalImagesData);
        }

        return parent::getImageHtml($imageView, $attributes);
    }
}
