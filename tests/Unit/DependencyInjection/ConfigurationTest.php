<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Mediapart\Bundle\LaPresseLibreBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    public function testConfigTreeBuilder()
    {
        $configuration = new Configuration();
        $tree = $configuration->getConfigTreeBuilder()->buildTree();

        $this->assertEquals('mediapart_la_presse_libre', $tree->getName());
        $this->assertEquals([
            'public_key',
            'secret_key',
            'aes_password',
            'aes_iv',
            'aes_options',
        ], array_keys($tree->getChildren()));
    }
}
