<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Mnemonic\Bip39;

use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39Mnemonic;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39SeedGenerator;
use BitWasp\Buffertools\BufferInterface;

class Bip39SeedGeneratorTest extends AbstractBip39Case
{
    /**
     * @dataProvider getBip39Vectors
     */
    public function test_mnemonic_to_seed(Bip39Mnemonic $bip39, BufferInterface $entropy, string $mnemonic, BufferInterface $eSeed)
    {
        $password = 'TREZOR';
        $seedGenerator = new Bip39SeedGenerator;
        $seed = $seedGenerator->getSeed($mnemonic, $password);
        $this->assertEquals($eSeed->getBinary(), $seed->getBinary());
    }
}
