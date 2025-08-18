<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Mnemonic;

use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39Mnemonic;
use BitWasp\Bitcoin\Mnemonic\Electrum\ElectrumMnemonic;
use BitWasp\Bitcoin\Mnemonic\MnemonicFactory;
use BitWasp\Bitcoin\Tests\AbstractTestCase;

class MnemonicFactoryTest extends AbstractTestCase
{
    public function test_get_electrum()
    {
        $this->assertInstanceOf(ElectrumMnemonic::class, MnemonicFactory::electrum());
    }

    public function test_get_bip39()
    {
        $this->assertInstanceOf(Bip39Mnemonic::class, MnemonicFactory::bip39());
    }
}
