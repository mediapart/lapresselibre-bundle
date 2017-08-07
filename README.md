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

Configure your App with your LaPresseLibre credentials:

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
  resource: "@MediapartLaPresseLibreBundle/Resources/config/routing.yml"
  #prefix: lapresselibre/
```

Define your domain:

```yaml
# app/config/services.yml

services:
  your_verification_service:
    class: Acme\LaPresseLibre\Verification
    arguments: 
      - %lapresselibre_publickey%
      tags:
        - { name: lapresselibre, route: "lapresselibre_verification", operation: 'Mediapart\LaPresseLibre\Operation\Verification', method: 'alwaysVerifiedAccounts' }
```

```php
<?php
# src/Acme/LaPresseLibre/Verification.php

namespace Acme\LaPresseLibre;

class Verification
{
    public function __construct($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    public function alwaysVerifiedAccounts(array $data, $isTesting = false)
    {
        $now = new \DateTime('next year');
        return [
            'Mail' => $data['Mail'],
            'CodeUtilisateur' => $data['CodeUtilisateur'],
            'TypeAbonnement' => 'mensuel',
            'DateExpiration' => $now->format("Y-m-d\TH:i:sO"),
            'DateSouscription' => $now->format("Y-m-d\TH:i:sO"),
            'AccountExist' => true,
            'PartenaireID' => $this->publicKey,
        ];
    }
}

```