# Shopsys Read Model

[![Build Status](https://travis-ci.org/shopsys/read-model.svg?branch=master)](https://travis-ci.org/shopsys/read-model)
[![Downloads](https://img.shields.io/packagist/dt/shopsys/read-model.svg)](https://packagist.org/packages/shopsys/read-model)

This bundle for [Shopsys Framework](https://www.shopsys-framework.com) separates templates from model using [read model concept](/docs/model/introduction-to-read-model.md).

This repository is maintained by [shopsys/shopsys] monorepo, information about changes are in [monorepo CHANGELOG.md](https://github.com/shopsys/shopsys/blob/master/CHANGELOG.md).

## Installation
The plugin is a Symfony bundle and is installed in the same way:

### Download
First, you download the package using [Composer](https://getcomposer.org/):
```
composer require shopsys/read-model
```

### Register
For the bundle to be loaded in your application you need to register it in the `app/AppKernel.php` file of your project:
```php
// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Shopsys\ProductFeed\ZboziBundle\ShopsysReadModelBundle(),
            // ...
        ];

        // ...

        return $bundles;
    }

    // ...
}
```

## Usage
If you want to leverage the advantages of read model concept, you need to use the particular implementation of `ListedProductViewFacadeInterface` in your controllers (there is already prepared one implementation in the bundle).
The facade provides you the view objects for product lists that can be then used in the templates.

### Available View Objects
- [`ListedProductView`](/packages/read-model/src/Product/Listed/ListedProductView.php) - product representation for FE product lists
- [`ActionView`](/packages/read-model/src/Product/Action/ProductActionView.php) - representation of product action area (i.e. form for adding a product to cart, or link to the product detail in the case of main variant)
- [`ImageView`](/packages/read-model/src/Image/ImageView.php) - representation of image

### Available Twig functions
- `image`
- `renderFlagsByIds`

See [`Twig`](/packages/read-model/src/Twig/) folder for implementation details.

### Example
<!--- TODO add link to commit with readmodel usage -->

## Contributing
Thank you for your contributions to Shopsys Read Model package.
Together we are making Shopsys Framework better.

This repository is READ-ONLY.
If you want to [report issues](https://github.com/shopsys/shopsys/issues/new) and/or send [pull requests](https://github.com/shopsys/shopsys/compare),
please use the main [Shopsys repository](https://github.com/shopsys/shopsys).

Please, check our [Contribution Guide](https://github.com/shopsys/shopsys/blob/master/CONTRIBUTING.md) before contributing.

## Support
What to do when you are in troubles or need some help? Best way is to contact us on our Slack [http://slack.shopsys-framework.com/](http://slack.shopsys-framework.com/)

If you want to [report issues](https://github.com/shopsys/shopsys/issues/new), please use the main [Shopsys repository](https://github.com/shopsys/shopsys).

[shopsys/shopsys]:(https://github.com/shopsys/shopsys)
