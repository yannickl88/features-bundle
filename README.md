# features-bundle
This Symfony bundle provides a way of managing features within a project. A common use-case is to have a certain feature only active under certain condition. Examples would be that you want to activate a feature when the use has a certain role, or when you are not in a production environment (think of testing).

With this bundle you can configure features to be active or inactive. Using resolvers you decide when a feature is active or not.

Requirements:
- PHP 7.3 or higher
- Symfony 4.2 or higher
 
Recommended installation is via composer: `composer require yannickl88/features-bundle`.

After that, you need to register the bundle in the kernel of your application:

```php
<?php
// app/AppKernel.php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Yannickl88\FeaturesBundle\FeaturesBundle(),
            // …
        ];
    }
}
```

# Usage
All configuration is done using services and your application config. For the following example we want to enable a feature when the GET parameter `beta` is set to `on`.

So configuring your feature in the `config.yml` of your application.

```yml
features:
    tags:
        beta: # our feature tag
            request: ["beta", "on"] # 'app.features.request_resolver' will resolve this key
```

Here we define a feature tag `beta` which will be resolved with the `request` resolver. Now we need to configure the `request` resolver. We do this with the following service definition:
```yml
services:
    app.features.request_resolver:
        class: App\Feature\RequestResolver
        arguments:
            - '@Yannickl88\FeaturesBundle\Feature\Feature'
        tags:
            # config-key is set to resolve the configured key: "request" with the options "beta" and "on"
            - { name: features.resolver, config-key: request }
```
Here we create the `app.features.request_resolver` service and tag it with `features.resolver`. This will then be picked up by the bundle and be registered so we can use it in our feature tags. What we also provide is a `config-key` value. This is the key that we defined in the `config.yml` under the `beta` tag. This will glue your config to your resolver.

Final thing to do is implement the `RequestResolver`:
```php
namespace App\Feature;

use Symfony\Component\HttpFoundation\RequestStack;
use Yannickl88\FeaturesBundle\Feature\FeatureResolverInterface;

class RequestResolver implements FeatureResolverInterface
{
    private $request_stack;
    
    public function __construct(RequestStack $request_stack)
    {
        $this->request_stack = $request_stack;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive(array $options = []): bool
    {
        // Feature is inactive when there is no request
        if (null === $request = $this->request_stack->getMasterRequest()) {
            return false;
        }

        // $options contains ["beta", "on"] for the 'beta' feature tag
        list($key, $expected_value) = $options;

        return $request->get($key) === $expected_value;
    }
}
```
Now we can start using the feature in our code. So if I want to check for a feature I can inject it as follows:
```yml
services:
    app.some.service:
       class: App\Some\Service
       arguments:
           - '@Yannickl88\FeaturesBundle\Feature\Feature'
       tags:
           - { name: features.tag, tag: beta }
```
Notice here that we do not inject the feature directly, but tag the service. The bundle will replace the feature for you. So you can use it as follows in your code:
```php
namespace App\Some;

use Yannickl88\FeaturesBundle\Feature\Feature;

class Service
{
    private $feature;
    
    public function __construct(Feature $feature)
    {
        $this->feature = $feature;
    }

    public function someMethod(): void
    {
        if ($this->feature->isActive()) {
            // do some extra beta logic when this feature is active
        }
    }
}
```
So if I now add `?beta=on` to my URL. The feature will trigger.

__Note:__ If you remove the tag, it will inject a deprecated feature. This deprecated feature will trigger a warning when the `isActive` is used so you will quickly see where unused feature are used.

# Twig
If it also possible to check a feature in your twig templates. Simply use the `feature` function to check if a feature is enabled.

```twig 
{% if feature("beta") %}
    {# do some extra beta logic when this feature is active #}
{% endif %}
```

# Advanced Topics
It is possible to configure multiple resolvers per feature tag. You can simply keep adding more in the `config.yml`. So in the example we can extend it to:
```yml
features:
    tags:
        beta:
            request: ["beta", "on"]
            other: ~
            more: ["foo"]
```
All resolvers must now resolve to `true` in order for this feature to be active. This is usefull if you want to check for multiple conditions.

Furthermore, if you want to have multiple resolvers where only one needs to resolve to `true`, you can use the chain resolver. This can be done as follows:
```yml
features:
    tags:
        beta:
            chain:
                request: ["beta", "on"]
                other: ~
                more: ["foo"]
```
Notice here we have as resolver `chain` and under this we have your config as before.
