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
            # the FQCN of the trait a class has to use to trigger injection
            trait: Fermio\Bundle\TraitInjectionBundle\Traits\ContainerAware
            method: setContainer # the method to call for service injection
            service: service_container # the id of the service to be injected
```

By default, all services implementing a specified trait will have a method call
added to their definition, except:

* the service is defined to be excluded in the configuration (see `excludes`)
* the service is synthetic (runtime injection, container cannot build it)
* the service has already an identical method call configured

## Usage

The bundle ships with some traits useful for every Symfony application. Take a
look in the `Traits/` directory for a comprehensive list of available traits.
Feel free to provide more useful traits via pull requests.

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
            invalid: exception # default
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

## Example

If you're not familiar with automatic dependency injection please read this
article about [interface injection][1] first. This feature is gone for several
reasons and I do not want to argue about that (I liked it in the first place).
Since PHP5.4 we have traits and the goal of this bundle is to solve the same
problems like the interface injection once solved: keep service configuration
simple and don't repeat yourself.

So here's a very basic example on how to use the bundle in the wild. Let there
be a small controller with an action that needs several services. We might
inject them via the constructor or via setters, the latter beeing somewhat
useless because we do not have any optional dependency. We would usually prefer
the constructor injection.

### Constructor injection

``` php
<?php
// App/Controller/SomeController.php
namespace App\Controller;

use App\Events\SomeEvent;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SomeController
{
    // copy/paste starts

    private $doctrine;
    private $eventDispatcher;
    private $urlGenerator;
    private $securityContext;

    public function __construct(RegistryInterface $doctrine, EventDispatcherInterface $eventDispatcher, UrlGeneratorInterface $urlGenerator, SecurityContextInterface $securityContext)
    {
        $this->doctrine = $doctrine;
        $this->eventDispatcher = $eventDispatcher;
        $this->urlGenerator = $urlGenerator;
        $this->securityContext = $securityContext;
    }

    // copy/paste ends

    public function someAction($id)
    {
        if (!$this->securityContext->isGranted('ROLE_ANY')) {
            return new RedirectResponse($this->urlGenerator->generate('homepage'));
        }

        $entity = $this->doctrine->getEntityManager('Some:Entity')->getRepository('Some:Entity')->find($id);
        $this->eventDispatcher->dispatch('some.event', new SomeEvent($entity));

        return ['entity' = $entity];
    }
}
```

Constructor injection would be configured as followed:

``` yaml
# App/Resources/config/services.yml
services:
    some.controller:
        class: App\Controller\SomeController
        # you shall not forget the constructor arguments,
        # service names, and correct sequence of order :)
        arguments:
            - '@doctrine'
            - '@event_dispatcher'
            - '@router'
            - '@security.context'
```

### Setter injection

``` php
<?php
// App/Controller/SomeController.php
namespace App\Controller;

use App\Events\SomeEvent;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SomeController
{
    // copy/paste starts

    private $doctrine;
    private $eventDispatcher;
    private $urlGenerator;
    private $securityContext;

    public function setDoctrine(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setUrlGenereator(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function setSecurityContext(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    // copy/paste ends

    public function someAction($id)
    {
        if (!$this->securityContext->isGranted('ROLE_ANY')) {
            return new RedirectResponse($this->urlGenerator->generate('homepage'));
        }

        $entity = $this->doctrine->getEntityManager('Some:Entity')->getRepository('Some:Entity')->find($id);
        $this->eventDispatcher->dispatch('some.event', new SomeEvent($entity));

        return ['entity' = $entity];
    }
}
```

Setter injection would be configured as followed:

``` yaml
# App/Resources/config/services.yml
services:
    some.controller:
        class: App\Controller\SomeController
        calls: # you shall not forget a method and service name
            - { method: setDoctrine, arguments: ['@doctrine'] }
            - { method: setEventDispatcher, arguments: ['@event_dispatcher'] }
            - { method: setUrlGenerator, arguments: ['@router'] }
            - { method: setSecurityContext, arguments: ['@security.context'] }
```

### Trait injection

``` php
<?php
// App/Controller/SomeController.php
namespace App\Controller;

use App\Events\SomeEvent;
use Fermio\Bundle\FermioTraitInjectionBundle\Traits;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SomeController
{
    use Traits\DoctrineAware;
    use Traits\EventDispatcherAware;
    use Traits\RouterAware;
    use Traits\SecurityContextAware;

    public function someAction($id)
    {
        if (!$this->getSecurityContext()->isGranted('ROLE_ANY')) {
            return new RedirectResponse($this->getRouter()->generate('homepage'));
        }

        $entity = $this->getDoctrine()->getEntityManager('Some:Entity')->getRepository('Some:Entity')->find($id);
        $this->getEventDispatcher()->dispatch('some.event', new SomeEvent($entity));

        return ['entity' = $entity];
    }
}
```

Instead of using constructor injection or setter injection we safely can rely on
the trait injection (which indeed is a setter injection, but once configured it
works for all services the same without configuring it again, again and again).
It also saves a lot of time in the long run because we must implement common and
shared functionality in traits only once and can re-use them everywhere.

``` yaml
# app/config/config.yml
fermio_trait_injection:
    traits:
        doctrine:
            trait: Fermio\Bundle\TraitInjectionBundle\Traits\DoctrineAware
            method: setDoctrine
            service: doctrine
        event_dispatcher:
            trait: Fermio\Bundle\TraitInjectionBundle\Traits\EventDispatcherAware
            method: setEventDispatcher
            service: event_dispatcher
        router:
            trait: Fermio\Bundle\TraitInjectionBundle\Traits\RouterAware
            method: setRouter
            service: router
        security_context:
            trait: Fermio\Bundle\TraitInjectionBundle\Traits\SecurityContextAware
            method: setSecurityContext
            service: security.context
        # ... even more traits
```

We can skip defining constructor injection (unless we have constructor arguments)
and setter injection (unless we have setter to be called additionally). The
bundle will add the correct method calls to the service configuration.

``` yaml
# App/Resources/config/services.yml
services:
    some_controller:
        class: App\Controller\SomeController
        # you shall concentrate on the code!
```

[1]: http://avalanche123.com/blog/2010/10/01/interface-injection-and-symfony2-dic/
