# `La Presse Libre` Bundle

## Installation

Install the package with Composer:

```
composer require mediapart/lapresselibre-bundle
```

Load the bundle into your Kernel:

```php
# app/AppKernel.php

$bundles = array(
    // ...
    new Meup\Bundle\LaPresseLibreBundle\MediapartLaPresseLibreBundle(),
);
```

Configure your App with your [LaPresseLibre partner credentials](https://partenaire.lapresselibre.fr/gestion/credentials):

```yaml
# app/config/config.yml

mediapart_lapresselibre:
    public_key:   %lapresselibre_publickey%
    secret_key:   %lapresselibre_secretkey%
    aes_password: %lapresselibre_aespassword%
    aes_iv:       %lapresselibre_aesiv%
```

Configure the routing:

```yaml
# app/config/routing.yml

MediapartLaPresseLibre:
  resource: "@MediapartLaPresseLibreBundle/Resources/config/routing.php"
  #prefix: lapresselibre/
```

And then, indicates your endpoints uri into [LaPresseLibre partner platform](https://partenaire.lapresselibre.fr/gestion).

Define what your endpoints have to do:

```php
<?php
# src/Acme/LaPresseLibre/Verification.php

namespace AppBundle\LaPresseLibre;

use Mediapart\LaPresseLibre\Subscription\Type as SubscriptionType;

class Verification
{
    private $public_key;

    public function __construct($public_key)
    {
        $this->public_key = $public_key;
    }

    public function alwaysVerifiedAccounts(array $data, $isTesting = false)
    {
        $now = new \DateTime('next year');
        return [
            'Mail' => $data['Mail'],
            'CodeUtilisateur' => $data['CodeUtilisateur'],
            'TypeAbonnement' => SubscriptionType::MONTHLY,
            'DateExpiration' => $now->format("Y-m-d\TH:i:sO"),
            'DateSouscription' => $now->format("Y-m-d\TH:i:sO"),
            'AccountExist' => true,
            'PartenaireID' => $this->public_key,
        ];
    }
}
```

```yaml
# app/config/services.yml

services:
  your_verification_service:
    class: 'AppBundle\LaPresseLibre\Verification'
    arguments: 
      - '%lapresselibre_publickey%'
      tags:
        - { name: 'lapresselibre', route: 'lapresselibre_verification', operation: 'Mediapart\LaPresseLibre\Operation\Verification', method: 'alwaysVerifiedAccounts' }
```
