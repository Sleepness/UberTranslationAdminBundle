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
        new Sleepness\UberTranslationBundle\SleepnessUberTranslationAdminBundle(),
    );
}
```

### Step 3: Routing configuration

In `app/config/routing.yml` you must to include bundle routes:

``` yml
uber:
    resource: "@SleepnessUberTranslationBundle/Resources/config/routing.yml"
```

### Step 4: Import translations into memcached by console command `uber:translations:import locale BundleName`

Example:

``` bash
$ php app/console uber:translations:import
```

### Step 5: Go to translation dashboard panel, you URL may look like:

 `www.examle.com/tanslations`
