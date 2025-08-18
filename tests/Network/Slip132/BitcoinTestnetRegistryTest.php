<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Network\Slip132;

use BitWasp\Bitcoin\Network\Networks\BitcoinTestnet;
use BitWasp\Bitcoin\Network\Slip132\BitcoinTestnetRegistry;
use BitWasp\Bitcoin\Script\ScriptType;
use BitWasp\Bitcoin\Tests\AbstractTestCase;

class BitcoinTestnetRegistryTest extends AbstractTestCase
{
    /**
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     * @throws \BitWasp\Bitcoin\Exceptions\MissingBip32Prefix
     */
    public function test_xpub_p2pkh()
    {
        $network = new BitcoinTestnet;
        $registry = new BitcoinTestnetRegistry;
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
    public function test_xpub_p2sh_p2pkh()
    {
        $network = new BitcoinTestnet;
        $registry = new BitcoinTestnetRegistry;
        [$priv, $pub] = $registry->getPrefixes(ScriptType::P2SH.'|'.ScriptType::P2PKH);

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
        $registry = new BitcoinTestnetRegistry;
        [$priv, $pub] = $registry->getPrefixes(ScriptType::P2SH.'|'.ScriptType::P2WKH);

        $this->assertEquals('044a5262', $pub);
        $this->assertEquals('044a4e28', $priv);
    }

    public function test_ypub_p2sh_p2wsh_p2pkh()
    {
        $this->expectExceptionMessage('Unknown script type');
        $this->expectException(\InvalidArgumentException::class);

        $registry = new BitcoinTestnetRegistry;
        $registry->getPrefixes(ScriptType::P2SH.'|'.ScriptType::P2WSH.'|'.ScriptType::P2PKH);
    }

    public function testzpub_p2wpkh()
    {
        $registry = new BitcoinTestnetRegistry;
        [$priv, $pub] = $registry->getPrefixes(ScriptType::P2WKH);

        $this->assertEquals('045f1cf6', $pub);
        $this->assertEquals('045f18bc', $priv);
    }

    public function test_zpub_p2sh_p2wsh_p2pkh()
    {
        $registry = new BitcoinTestnetRegistry;
        [$priv, $pub] = $registry->getPrefixes(ScriptType::P2WSH.'|'.ScriptType::P2PKH);

        $this->assertEquals('02575483', $pub);
        $this->assertEquals('02575048', $priv);
    }
}
