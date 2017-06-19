Doublespark news categories bundle
==================================
This bundle adds categories to the Contao news bundle.

Usage
-----
1. Manage categories from "News categories" on CMS left menu
2. Select categories when creating a news item
3. Place 'News list by category' module on a page to view news items by category
4. Place 'News category navigation' module to create news category navigation

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require doublespark/news-categories-bundle "~1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Doublespark\NewsCategoriesBundle\DoublesparkNewsCategoriesBundle(),
        );

        // ...
    }

    // ...
}
```
