<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Network\Slip132;

use BitWasp\Bitcoin\Network\Networks\Bitcoin;
use BitWasp\Bitcoin\Network\Slip132\BitcoinRegistry;
use BitWasp\Bitcoin\Script\ScriptType;
use BitWasp\Bitcoin\Tests\AbstractTestCase;

class BitcoinRegistryTest extends AbstractTestCase
{
    /**
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     * @throws \BitWasp\Bitcoin\Exceptions\MissingBip32Prefix
     */
    public function test_xpub_p2pkh()
    {
        $network = new Bitcoin;
        $registry = new BitcoinRegistry;
        [$priv, $pub] = $registry->getPrefixes(ScriptType::P2PKH);

        $this->assertEquals(
            $network->getHDPubByte(),
            $pub
        );

        $this->assertEquals(
            $network->getHDPrivByte(),
            $priv
        );
    }

    /**
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     * @throws \BitWasp\Bitcoin\Exceptions\MissingBip32Prefix
     */
    public function test_xpub_p2sh_multisig()
    {
        $network = new Bitcoin;
        $registry = new BitcoinRegistry;
        [$priv, $pub] = $registry->getPrefixes(ScriptType::P2SH.'|'.ScriptType::MULTISIG);

        $this->assertEquals(
            $network->getHDPubByte(),
            $pub
        );

        $this->assertEquals(
            $network->getHDPrivByte(),
            $priv
        );
    }

    public function testypub_p2sh_p2wpkh()
    {
        $registry = new BitcoinRegistry;
        [$priv, $pub] = $registry->getPrefixes(ScriptType::P2SH.'|'.ScriptType::P2WKH);

        $this->assertEquals('049d7cb2', $pub);
        $this->assertEquals('049d7878', $priv);
    }

    public function test_ypub_p2sh_p2wsh_p2pkh()
    {
        $registry = new BitcoinRegistry;
        [$priv, $pub] = $registry->getPrefixes(ScriptType::P2SH.'|'.ScriptType::P2WSH.'|'.ScriptType::MULTISIG);

        $this->assertEquals('0295b43f', $pub);
        $this->assertEquals('0295b005', $priv);
    }

    public function testzpub_p2wpkh()
    {
        $registry = new BitcoinRegistry;
        [$priv, $pub] = $registry->getPrefixes(ScriptType::P2WKH);

        $this->assertEquals('04b24746', $pub);
        $this->assertEquals('04b2430c', $priv);
    }

    public function test_zpub_p2wsh_p2pkh()
    {
        $registry = new BitcoinRegistry;
        [$priv, $pub] = $registry->getPrefixes(ScriptType::P2WSH.'|'.ScriptType::MULTISIG);

        $this->assertEquals('02aa7ed3', $pub);
        $this->assertEquals('02aa7a99', $priv);
    }
}
