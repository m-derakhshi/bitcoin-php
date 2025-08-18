<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Network;

use BitWasp\Bitcoin\Exceptions\MissingBip32Prefix;
use BitWasp\Bitcoin\Network\Network;
use BitWasp\Bitcoin\Network\Networks\Bitcoin;
use BitWasp\Bitcoin\Tests\AbstractTestCase;

class Bip32PrefixTest extends AbstractTestCase
{
    public function test_has_known_bip32_prefix()
    {
        $method = new \ReflectionMethod(Bitcoin::class, 'hasBip32Prefix');
        $method->setAccessible(true);
        $hasPrefix = $method->invoke(new Bitcoin, Network::BIP32_PREFIX_XPUB);
        $this->assertTrue($hasPrefix);
    }

    public function test_has_unknown_bip32_prefix()
    {
        $method = new \ReflectionMethod(Bitcoin::class, 'hasBip32Prefix');
        $method->setAccessible(true);
        $hasPrefix = $method->invoke(new Bitcoin, 'unknown-prefix');
        $this->assertFalse($hasPrefix);
    }

    public function test_get_known_bip32_prefix()
    {
        $method = new \ReflectionMethod(Bitcoin::class, 'getBip32Prefix');
        $method->setAccessible(true);
        $prefix = $method->invoke(new Bitcoin, Network::BIP32_PREFIX_XPUB);
        $this->assertSame('0488b21e', $prefix);
    }

    public function test_get_unknown_bip32_prefix()
    {
        $method = new \ReflectionMethod(Bitcoin::class, 'getBip32Prefix');
        $method->setAccessible(true);

        $this->expectException(MissingBip32Prefix::class);

        $method->invoke(new Bitcoin, 'unknown-prefix');
    }

    public function test_get_bip32_type_byte()
    {
        $network = new Bitcoin;
        $method = new \ReflectionProperty(Bitcoin::class, 'bip32PrefixMap');
        $method->setAccessible(true);

        $map = $value = $method->getValue($network);
        $this->assertEquals($map[Network::BIP32_PREFIX_XPUB], $network->getHDPubByte());
        $this->assertEquals($map[Network::BIP32_PREFIX_XPRV], $network->getHDPrivByte());
    }
}
