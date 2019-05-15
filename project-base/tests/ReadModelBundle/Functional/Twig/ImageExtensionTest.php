<?php

declare(strict_types=1);

namespace Tests\ReadModelBundle\Functional\Twig;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Image\AdditionalImageData;
use Shopsys\FrameworkBundle\Component\Image\ImageFacade;
use Shopsys\FrameworkBundle\Twig\ImageExtension as FrameworkImageExtension;
use Shopsys\ReadModelBundle\Image\ImageView;
use Shopsys\ReadModelBundle\Twig\ImageExtension;
use Tests\ShopBundle\Test\FunctionalTestCase;

class ImageExtensionTest extends FunctionalTestCase
{
    public function testGetImageHtmlWithMockedImageFacade(): void
    {
        $frameworkBundleImageExtension = $this->getContainer()->get(FrameworkImageExtension::class);
        $domain = $this->getContainer()->get(Domain::class);

        $productId = 2;
        $entityName = 'product';
        $extension = 'jpg';

        $imageFacade = $this->createMock(ImageFacade::class);
        $imageFacade->method('getImageUrlNotUsingImageOrEntity')->willReturn(sprintf('http://webserver:8080/%s/%d.%s', $entityName, $productId, $extension));
        $imageFacade->method('getAdditionalImagesDataNotUsingImageOrEntity')->willReturn([
            new AdditionalImageData('(min-width: 1200px)', sprintf('http://webserver:8080/%s/additional_0_%d.%s', $entityName, $productId, $extension)),
            new AdditionalImageData('(max-width: 480px)', sprintf('http://webserver:8080/%s/additional_1_%d.%s', $entityName, $productId, $extension)),
        ]);

        $imageView = new ImageView($productId, $extension, $entityName, null);

        $readModelBundleImageExtension = new ImageExtension($frameworkBundleImageExtension, $imageFacade, $domain);
        $html = $readModelBundleImageExtension->getImageHtml($imageView);

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/Resources/picture.twig', $html);

        libxml_clear_errors();
    }

    public function testGetImageHtml(): void
    {
        $frameworkBundleImageExtension = $this->getContainer()->get(FrameworkImageExtension::class);
        $domain = $this->getContainer()->get(Domain::class);
        $imageFacade = $this->getContainer()->get(ImageFacade::class);

        $productId = 1;
        $entityName = 'product';
        $extension = 'jpg';

        $imageView = new ImageView($productId, $extension, $entityName, null);

        $readModelBundleImageExtension = new ImageExtension($frameworkBundleImageExtension, $imageFacade, $domain);
        $html = $readModelBundleImageExtension->getImageHtml($imageView);

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/Resources/picture-facade.twig', $html);

        libxml_clear_errors();
    }
}
