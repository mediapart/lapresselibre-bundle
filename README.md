# `La Presse Libre` Symfony Bundle

[![Build Status](https://secure.travis-ci.org/mediapart/lapresselibre-bundle.svg?branch=master)](http://travis-ci.org/mediapart/lapresselibre-bundle) [![Code Coverage](https://codecov.io/gh/mediapart/lapresselibre-bundle/branch/master/graph/badge.svg)](https://scrutinizer-ci.com/g/mediapart/lapresselibre-bundle) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mediapart/lapresselibre-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mediapart/lapresselibre-bundle) [![Total Downloads](https://poser.pugx.org/mediapart/lapresselibre-bundle/downloads.png)](https://packagist.org/packages/mediapart/lapresselibre-bundle) [![Latest Stable Version](https://poser.pugx.org/mediapart/lapresselibre-bundle/v/stable.png)](https://packagist.org/packages/mediapart/lapresselibre-bundle)

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
    new Mediapart\Bundle\LaPresseLibreBundle\MediapartLaPresseLibreBundle(),
);
```

Configure your App with your [LaPresseLibre partner credentials](https://partenaire.lapresselibre.fr/gestion/credentials):

```yaml
# app/config/config.yml

mediapart_la_presse_libre:
    public_key:   %lapresselibre_publickey%
    secret_key:   %lapresselibre_secretkey%
    aes_password: %lapresselibre_aespassword%
    aes_iv:       %lapresselibre_aesiv%
    
# correspondance avec le json des credentials:
#  public_key : "CodePartenaire"
#  secret_key: "Secret"
#  aes_password: "Aes"
#  aes_iv: "Iv"
```

### WebServices

Configure the routing:

```yaml
# app/config/routing.yml

MediapartLaPresseLibreWebServices:
  resource: "@MediapartLaPresseLibreBundle/Resources/config/routing/webservices.php"
  #prefix: lapresselibre/
```

And then, indicates your endpoints uri into [LaPresseLibre partner platform](https://partenaire.lapresselibre.fr/gestion).

Define what your endpoints have to do.
For example, Your [verification endpoint](https://github.com/NextINpact/LaPresseLibreSDK/wiki/Fonctionnement-des-web-services#web-service-de-v%C3%A9rification-de-comptes-existants) will look like :

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

### Link Account

https://github.com/NextINpact/LaPresseLibreSDK/wiki/Liaison-de-compte-utilisateur-par-redirection

Configure the routing:

```yaml
# app/config/routing.yml

MediapartLaPresseLibreLinkAccount:
  resource: "@MediapartLaPresseLibreBundle/Resources/config/routing/link-account.php"
  #prefix: lapresselibre/
```

Implements `Mediapart/LaPresseLibre/Account/Repository` and `Mediapart\Bundle\LaPresseLibreBundle\Account\AccountProvider` interfaces.

```yaml
# app/config/services.yml

services:
  # …
  your_account_repository:
    class: 'AppBundle\LaPresseLibre\AccountRepository'
  your_account_provider:
    class: 'AppBundle\LaPresseLibre\AccountProvider'
```

Update the config :

```yaml
# app/config/config.yml

mediapart_lapresselibre:
    # …
    account:
        repository: 'your_account_repository'
        provider: 'your_account_provider'
```



