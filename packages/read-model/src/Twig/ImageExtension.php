<?php

declare(strict_types=1);

namespace Shopsys\ReadModelBundle\Twig;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Image\ImageFacade;
use Shopsys\FrameworkBundle\Twig\ImageExtension as BaseImageExtension;
use Shopsys\ReadModelBundle\Image\ImageViewInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ImageExtension extends AbstractExtension
{
    /**
     * @var \Shopsys\FrameworkBundle\Twig\ImageExtension
     */
    protected $baseImageExtension;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Image\ImageFacade
     */
    protected $imageFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected $domain;

    /**
     * @param \Shopsys\FrameworkBundle\Twig\ImageExtension $baseImageExtension
     * @param \Shopsys\FrameworkBundle\Component\Image\ImageFacade $imageFacade
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     */
    public function __construct(BaseImageExtension $baseImageExtension, ImageFacade $imageFacade, Domain $domain)
    {
        $this->baseImageExtension = $baseImageExtension;
        $this->imageFacade = $imageFacade;
        $this->domain = $domain;
    }

    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('image', [$this, 'getImageHtml'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param \Shopsys\FrameworkBundle\Component\Image\Image|\Shopsys\ReadModelBundle\Image\ImageViewInterface|Object|null $imageView
     * @param array $attributes
     * @return string
     */
    public function getImageHtml($imageView, array $attributes = []): string
    {
        $this->baseImageExtension->preventDefault($attributes);

        try {
            if ($imageView instanceof ImageViewInterface) {
                $attributes['src'] = $this->imageFacade->getImageUrlNotUsingImageOrEntity(
                    $this->domain->getCurrentDomainConfig(),
                    $imageView->getId(),
                    $imageView->getExtension(),
                    $entityName = $imageView->getEntityName(),
                    $imageView->getType(),
                    $attributes['size']
                );
                $additionalImagesData = $this->imageFacade->getAdditionalImagesDataNotUsingImageOrEntity(
                    $this->domain->getCurrentDomainConfig(),
                    $imageView->getId(),
                    $imageView->getExtension(),
                    $entityName = $imageView->getEntityName(),
                    $imageView->getType(),
                    $attributes['size']
                );
            } elseif ($imageView === null) {
                throw new \Shopsys\FrameworkBundle\Component\Image\Exception\ImageNotFoundException();
            } else {
                return $this->baseImageExtension->getImageHtml($imageView, $attributes);
            }
        } catch (\Shopsys\FrameworkBundle\Component\Image\Exception\ImageNotFoundException $e) {
            $entityName = 'noimage';
            $attributes['src'] = $this->baseImageExtension->getEmptyImageUrl();
            $additionalImagesData = [];
        }

        return $this->baseImageExtension->getImageHtmlByEntityName($attributes, $entityName, $additionalImagesData);
    }
}
