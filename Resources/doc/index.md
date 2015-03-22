Getting Started With SleepnessUberTranslationAdminBundle
==================================

## Installation

### Step 1: Download bundle using composer

Add SleepnessUberTranslationAdminBundle by running the command:

``` bash
$ php composer.phar require sleepness/uber-translation-bundle "@dev"
```

Composer will install the bundle to your project's `vendor/sleepness` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sleepness\UberTranslationAdminBundle\SleepnessUberTranslationAdminBundle(),
    );
}
```

### Step 3: Routing configuration

In `app/config/routing.yml` add routing configuration:

``` yml
uber:
    resource: "@SleepnessUberTranslationBundle/Resources/config/routing.yml"
```

### Step 4: Prerequisites

The bundle requires `KnpPaginatorBundle`. For more information about paginator, check [KnpPaginatorBundle documentation](https://github.com/KnpLabs/KnpPaginatorBundle/blob/master/README.md).

### Step 5: Import translations

Import translations into memcached by running console command `uber:translations:import locale BundleName`

Example:

``` bash
$ php app/console uber:translations:import en,uk AcmeDemoBundle
```

### Step 6: Translation dashboard panel

Go to translation dashboard panel. You URL may look like: `www.example.com/translations`
