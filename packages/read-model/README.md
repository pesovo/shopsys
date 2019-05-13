# Shopsys Read Model

[![Build Status](https://travis-ci.org/shopsys/read-model.svg?branch=master)](https://travis-ci.org/shopsys/read-model)
[![Downloads](https://img.shields.io/packagist/dt/shopsys/read-model.svg)](https://packagist.org/packages/shopsys/read-model)

This bundle for [Shopsys Framework](https://www.shopsys-framework.com) separate templates from model with read model concept.

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
