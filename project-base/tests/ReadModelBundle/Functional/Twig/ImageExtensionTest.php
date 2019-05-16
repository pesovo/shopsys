<?php

declare(strict_types=1);

namespace Tests\ReadModelBundle\Functional\Twig;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Image\AdditionalImageData;
use Shopsys\FrameworkBundle\Component\Image\ImageFacade;
use Shopsys\FrameworkBundle\Component\Image\ImageLocator;
use Shopsys\ReadModelBundle\Image\ImageView;
use Shopsys\ReadModelBundle\Twig\ImageExtension;
use Tests\ShopBundle\Test\FunctionalTestCase;

class ImageExtensionTest extends FunctionalTestCase
{
    public function testGetImageHtmlWithMockedImageFacade(): void
    {
        $domain = $this->getContainer()->get(Domain::class);
        $imageLocator = $this->getContainer()->get(ImageLocator::class);
        $templating = $this->getContainer()->get('templating');

        $productId = 2;
        $entityName = 'product';
        $extension = 'jpg';

        $imageFacadeMock = $this->createMock(ImageFacade::class);
        $imageFacadeMock->method('getImageUrlFromAttributes')->willReturn(sprintf('http://webserver:8080/%s/%d.%s', $entityName, $productId, $extension));
        $imageFacadeMock->method('getAdditionalImagesDataFromAttributes')->willReturn([
            new AdditionalImageData('(min-width: 1200px)', sprintf('http://webserver:8080/%s/additional_0_%d.%s', $entityName, $productId, $extension)),
            new AdditionalImageData('(max-width: 480px)', sprintf('http://webserver:8080/%s/additional_1_%d.%s', $entityName, $productId, $extension)),
        ]);

        $imageView = new ImageView($productId, $extension, $entityName, null);

        $readModelBundleImageExtension = new ImageExtension('', $domain, $imageLocator, $imageFacadeMock, $templating);

        $html = $readModelBundleImageExtension->getImageHtml($imageView);

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/Resources/picture.twig', $html);

        libxml_clear_errors();
    }

    public function testGetImageHtml(): void
    {
        $domain = $this->getContainer()->get(Domain::class);
        $imageLocator = $this->getContainer()->get(ImageLocator::class);
        $templating = $this->getContainer()->get('templating');

        $imageFacade = $this->getContainer()->get(ImageFacade::class);

        $productId = 1;
        $entityName = 'product';
        $extension = 'jpg';

        $imageView = new ImageView($productId, $extension, $entityName, null);

        $readModelBundleImageExtension = new ImageExtension('', $domain, $imageLocator, $imageFacade, $templating);
        $html = $readModelBundleImageExtension->getImageHtml($imageView);

        $url = $domain->getCurrentDomainConfig()->getUrl();

        $expected = '<picture>';
        $expected .= sprintf('    <source media="(min-width: 480px) and (max-width: 768px)" srcset="%s/content-test/images/product/default/additional_0_1.jpg"/>', $url);
        $expected .= sprintf('    <img alt="" class="image-product" itemprop="image" src="%s/content-test/images/product/default/1.jpg" title=""/>', $url);
        $expected .= '</picture>';

        $this->assertXmlStringEqualsXmlString($expected, $html);

        libxml_clear_errors();
    }

    public function testGetNoImageHtml(): void
    {
        $domain = $this->getContainer()->get(Domain::class);
        $imageLocator = $this->getContainer()->get(ImageLocator::class);
        $templating = $this->getContainer()->get('templating');

        $imageFacade = $this->getContainer()->get(ImageFacade::class);

        $readModelBundleImageExtension = new ImageExtension('', $domain, $imageLocator, $imageFacade, $templating);
        $html = $readModelBundleImageExtension->getImageHtml(null);

        $url = $domain->getCurrentDomainConfig()->getUrl();

        $expected = '<picture>';
        $expected .= sprintf('    <img alt="" class="image-noimage" title=""  itemprop="image" src="%s/noimage.png"/>', $url);
        $expected .= '</picture>';

        $this->assertXmlStringEqualsXmlString($expected, $html);

        libxml_clear_errors();
    }

    public function testGetNoImageHtmlWithDefaultFrontDesignImageUrlPrefix(): void
    {
        $defaultFrontDesignImageUrlPrefix = '/assets/frontend/images/';

        $domain = $this->getContainer()->get(Domain::class);
        $imageLocator = $this->getContainer()->get(ImageLocator::class);
        $templating = $this->getContainer()->get('templating');

        $imageFacade = $this->getContainer()->get(ImageFacade::class);

        $readModelBundleImageExtension = new ImageExtension($defaultFrontDesignImageUrlPrefix, $domain, $imageLocator, $imageFacade, $templating);
        $html = $readModelBundleImageExtension->getImageHtml(null);

        $url = $domain->getCurrentDomainConfig()->getUrl();

        $expected = '<picture>';
        $expected .= sprintf('    <img alt="" class="image-noimage" title=""  itemprop="image" src="%s%snoimage.png"/>', $url, $defaultFrontDesignImageUrlPrefix);
        $expected .= '</picture>';

        $this->assertXmlStringEqualsXmlString($expected, $html);

        libxml_clear_errors();
    }
}
