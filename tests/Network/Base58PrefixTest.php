<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Network;

use BitWasp\Bitcoin\Exceptions\MissingBase58Prefix;
use BitWasp\Bitcoin\Network\Network;
use BitWasp\Bitcoin\Network\Networks\Bitcoin;
use BitWasp\Bitcoin\Tests\AbstractTestCase;

class Base58PrefixTest extends AbstractTestCase
{
    public function test_has_known_base58_byte()
    {
        $method = new \ReflectionMethod(Bitcoin::class, 'hasBase58Prefix');
        $method->setAccessible(true);
        $hasPrefix = $method->invoke(new Bitcoin, Network::BASE58_ADDRESS_P2PKH);
        $this->assertTrue($hasPrefix);
    }

    public function test_has_unknown_base58_byte()
    {
        $method = new \ReflectionMethod(Bitcoin::class, 'hasBase58Prefix');
        $method->setAccessible(true);
        $hasPrefix = $method->invoke(new Bitcoin, "don't know this one");
        $this->assertFalse($hasPrefix);
    }

    public function test_get_known_base58_byte()
    {
        $method = new \ReflectionMethod(Bitcoin::class, 'getBase58Prefix');
        $method->setAccessible(true);
        $prefix = $method->invoke(new Bitcoin, Network::BASE58_ADDRESS_P2PKH);
        $this->assertSame('00', $prefix);
    }

    public function test_get_unknown_base58_byte()
    {
        $method = new \ReflectionMethod(Bitcoin::class, 'getBase58Prefix');
        $method->setAccessible(true);

        $this->expectException(MissingBase58Prefix::class);

        $method->invoke(new Bitcoin, 'unknown!');
    }

    public function test_get_base58_type_byte()
    {
        $network = new Bitcoin;
        $method = new \ReflectionProperty(Bitcoin::class, 'base58PrefixMap');
        $method->setAccessible(true);

        $map = $value = $method->getValue($network);
        $this->assertEquals($map[Network::BASE58_ADDRESS_P2SH], $network->getP2shByte());
        $this->assertEquals($map[Network::BASE58_ADDRESS_P2PKH], $network->getAddressByte());
    }
}
