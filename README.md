# `La Presse Libre` Bundle

## Installation

Install the package with Composer :

```
composer require mediapart/lapresselibre-bundle
```

Update `app/AppKernel.php` :

```php
$bundles = array(
    // ...
    new Meup\Bundle\LaPresseLibreBundle\MediapartLaPresseLibreBundle(),
);
```

```yaml
# app/config/config.yml

mediapart_lapresselibre:
    public_key:   2
    secret_key:   "mGoMuzoX8u"
    aes_password: "UKKzV7sxiGx3uc0auKrUO2kJTT2KSCeg"
    aes_iv:       "7405589013321961"
```

```yaml
# app/config/routing.yml

MediapartLaPresseLibre:
  resource: "@MediapartLaPresseLibreBundle/Resources/config/routing.yml"
```

```yaml
# app/config/services.yml

services:
    your_verification_service:
        class: Acme\LaPresseLibre\Verification
        tags:
              - { name: lapresselibre, route: "lapresselibre_verification", operation: 'Mediapart\LaPresseLibre\Operation\Verification', method: 'alwaysVerifiedAccounts' }
```

```php
<?php

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