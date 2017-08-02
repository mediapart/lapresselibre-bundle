<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Functional\App;

class VerificationService
{
	public function __construct($publicKey)
	{
		$this->publicKey = $publicKey;
	}

	public function execute(array $data, $isTesting = false)
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
