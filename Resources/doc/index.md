# FermioTraitInjectionBundle

Symfony bundle for trait injection.

## Requirements

FermioTraitInjectionBundle is only supported on PHP 5.4 and up.

## Installation

Add FermioTraitInjectionBundle in your composer.json:

``` js
{
    "require": {
        "fermio/trait-injection-bundle": "*"
    }
}
```

### Download the FermioTraitInjectionBundle

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update fermio/trait-injection-bundle
```

Composer will install the bundle to your project's `vendor/fermio` directory.

### Enable the FermioTraitInjectionBundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Fermio\Bundle\TraitInjectionBundle\FermioTraitInjectionBundle(),
    );
}
```

## Configuration

Add the following configuration to your `config.yml` file according to which
services you want to be injected for a specific trait.

``` yaml
# app/config/config.yml
fermio_trait_injection:
    traits:
        container:
            trait: Fermio\Bundle\TraitInjectionBundle\Traits\ContainerAware
            method: setContainer
            service: service_container
```

By default, all services implementing a specified trait will have a method call
added to their definition, except:

* the service is defined to be skipped in the configuration (see `skip`)
* the service is synthetic (runtime injection, container cannot build it)
* the service has already an identical method call configured

## Advanced Usage

Besides the trait injection there are some options to gain a coarser-grained
control over the bundle.

### Disable automatic injection

If you want to disable the trait injection for specific services (e.g. inject
another implementation) you can do that by simply adding the service id to the
`excludes` configuration:

``` yaml
# app/config/config.yml
fermio_trait_injection:
    excludes: ['my.service.id', 'my.other.service.id', ]
```

### Invalid reference behavior

The dependency injection container itself can handle invalid service reference
behaviors. The bundle considers same functionality.

#### Exception

Throws an exception if the referenced service does not exist. This is the default
behavior.

``` yaml
# app/config/config.yml
fermio_trait_injection:
    traits:
        container:
            trait: Fermio\Bundle\TraitInjectionBundle\Traits\ContainerAware
            method: setContainer
            service: service_container
            invalid: exception
```

#### Ignore

Ignores the trait injection completely if the referenced service does not exist.

``` yaml
# app/config/config.yml
fermio_trait_injection:
    traits:
        translator:
            trait: Fermio\Bundle\TraitInjectionBundle\Traits\TranslatorAware
            method: setTranslator
            service: translator
            invalid: ignore
```

#### Null

Injects `null` if the referenced service does not exist (for optional
dependencies, e.g. like services that are not available in all environments).

``` yaml
# app/config/config.yml
fermio_trait_injection:
    traits:
        logger:
            trait: Fermio\Bundle\TraitInjectionBundle\Traits\LoggerAware
            method: setLogger
            service: logger
            invalid: null
```
