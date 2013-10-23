## Installation

Installation is a quick 3 step process:

1. Download ScribePheanstalkBundle using composer
2. Enable the Bundle
3. Configure your application's config.yml

### Step 1: Download ScribePheanstalkBundle

Add ScribePheanstalkBundle in your composer.json:

```js
{
    "require": {
        "scribe/pheanstalk-bundle": "2.*"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update scribe/pheanstalk-bundle
```

Composer will install the bundle to your project's `vendor/scribe` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Scribe\PheanstalkBundle\ScribePheanstalkBundle(),
    );
}
```

### Step 3: Configure your application's config.yml

Finally, add the following to your config.yml

``` yaml
# app/config/config.yml
scribe_pheanstalk:
    enabled: true
    pheanstalks:
        primary:
            server: beanstalkd.domain.tld
            default: true
```
