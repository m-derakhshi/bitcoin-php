<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Key\Deterministic\Slip132;

use BitWasp\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use BitWasp\Bitcoin\Key\Deterministic\Slip132\Slip132;
use BitWasp\Bitcoin\Key\KeyToScript\KeyToScriptHelper;
use BitWasp\Bitcoin\Network\Slip132\BitcoinRegistry;
use BitWasp\Bitcoin\Script\ScriptType;
use BitWasp\Bitcoin\Tests\AbstractTestCase;

class Slip132Test extends AbstractTestCase
{
    /**
     * @dataProvider getEcAdapters
     *
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     */
    public function test_xpub_p2pkh(EcAdapterInterface $adapter)
    {
        $slip132 = new Slip132(new KeyToScriptHelper($adapter));
        $registry = new BitcoinRegistry;
        $prefix = $slip132->p2pkh($registry);

        [$priv, $pub] = $registry->getPrefixes($prefix->getScriptDataFactory()->getScriptType());
        $this->assertEquals($pub, $prefix->getPublicPrefix());
        $this->assertEquals($priv, $prefix->getPrivatePrefix());

        $factory = $prefix->getScriptDataFactory();
        $this->assertEquals(
            ScriptType::P2PKH,
            $factory->getScriptType()
        );
    }

    /**
     * @dataProvider getEcAdapters
     *
     * @throws \BitWasp\Bitcoin\Exceptions\DisallowedScriptDataFactoryException
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     */
    public function testypub_p2sh_p2wpkh(EcAdapterInterface $adapter)
    {
        $slip132 = new Slip132(new KeyToScriptHelper($adapter));
        $registry = new BitcoinRegistry;
        $prefix = $slip132->p2shP2wpkh($registry);

        [$priv, $pub] = $registry->getPrefixes($prefix->getScriptDataFactory()->getScriptType());
        $this->assertEquals($pub, $prefix->getPublicPrefix());
        $this->assertEquals($priv, $prefix->getPrivatePrefix());

        $factory = $prefix->getScriptDataFactory();
        $this->assertEquals(
            ScriptType::P2SH.'|'.ScriptType::P2WKH,
            $factory->getScriptType()
        );
    }

    /**
     * @dataProvider getEcAdapters
     *
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     */
    public function testzpub_p2wpkh(EcAdapterInterface $adapter)
    {
        $slip132 = new Slip132(new KeyToScriptHelper($adapter));
        $registry = new BitcoinRegistry;
        $prefix = $slip132->p2wpkh($registry);

        [$priv, $pub] = $registry->getPrefixes($prefix->getScriptDataFactory()->getScriptType());
        $this->assertEquals($pub, $prefix->getPublicPrefix());
        $this->assertEquals($priv, $prefix->getPrivatePrefix());

        $factory = $prefix->getScriptDataFactory();
        $this->assertEquals(
            ScriptType::P2WKH,
            $factory->getScriptType()
        );
    }

    /**
     * @dataProvider getEcAdapters
     *
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     */
    public function test_ypub_p2wsh_multisig(EcAdapterInterface $adapter)
    {
        $slip132 = new Slip132(new KeyToScriptHelper($adapter));
        $registry = new BitcoinRegistry;
        $prefix = $slip132->p2wshMultisig(1, 1, true, $registry);

        [$priv, $pub] = $registry->getPrefixes($prefix->getScriptDataFactory()->getScriptType());
        $this->assertEquals($pub, $prefix->getPublicPrefix());
        $this->assertEquals($priv, $prefix->getPrivatePrefix());

        $factory = $prefix->getScriptDataFactory();
        $this->assertEquals(
            ScriptType::P2WSH.'|'.ScriptType::MULTISIG,
            $factory->getScriptType()
        );
    }

    /**
     * @dataProvider getEcAdapters
     *
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     */
    public function test_zpub_p2sh_p2wsh_multisig(EcAdapterInterface $adapter)
    {
        $slip132 = new Slip132(new KeyToScriptHelper($adapter));
        $registry = new BitcoinRegistry;
        $prefix = $slip132->p2shP2wshMultisig(1, 1, true, $registry);

        [$priv, $pub] = $registry->getPrefixes($prefix->getScriptDataFactory()->getScriptType());
        $this->assertEquals($pub, $prefix->getPublicPrefix());
        $this->assertEquals($priv, $prefix->getPrivatePrefix());

        $factory = $prefix->getScriptDataFactory();
        $this->assertEquals(
            ScriptType::P2SH.'|'.ScriptType::P2WSH.'|'.ScriptType::MULTISIG,
            $factory->getScriptType()
        );
    }
}
