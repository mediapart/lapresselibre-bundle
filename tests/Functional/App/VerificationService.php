<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Functional\App;

use Mediapart\LaPresseLibre\Subscription\Type as SubscriptionType;

class VerificationService
{
    private $public_key;

    public function __construct($public_key)
    {
        $this->public_key = $public_key;
    }

    public function execute(array $data, $isTesting = false)
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
