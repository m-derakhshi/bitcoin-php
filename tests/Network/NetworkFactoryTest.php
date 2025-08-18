<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Network;

use BitWasp\Bitcoin\Network\NetworkFactory;
use BitWasp\Bitcoin\Network\Networks;
use BitWasp\Bitcoin\Tests\AbstractTestCase;

class NetworkFactoryTest extends AbstractTestCase
{
    public function getFactoryMethodAndClass(): array
    {
        return [
            ['bitcoin', Networks\Bitcoin::class],
            ['bitcoinTestnet', Networks\BitcoinTestnet::class],
            ['bitcoinRegtest', Networks\BitcoinRegtest::class],
            ['dash', Networks\Dash::class],
            ['dashTestnet', Networks\DashTestnet::class],
            ['dogecoin', Networks\Dogecoin::class],
            ['dogecoinTestnet', Networks\DogecoinTestnet::class],
            ['litecoin', Networks\Litecoin::class],
            ['litecoinTestnet', Networks\LitecoinTestnet::class],
            ['viacoin', Networks\Viacoin::class],
            ['viacoinTestnet', Networks\ViacoinTestnet::class],
        ];
    }

    /**
     * @dataProvider getFactoryMethodAndClass
     */
    public function test_network_factory(string $method, string $expectedClass)
    {
        $this->assertInstanceOf($expectedClass, call_user_func([NetworkFactory::class, $method]));
    }
}
