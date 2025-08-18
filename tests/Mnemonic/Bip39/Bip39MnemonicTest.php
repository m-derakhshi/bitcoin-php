<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Mnemonic\Bip39;

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39Mnemonic;
use BitWasp\Bitcoin\Mnemonic\Bip39\Wordlist\EnglishWordList;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class Bip39MnemonicTest extends AbstractBip39Case
{
    /**
     * @dataProvider getBip39Vectors
     */
    public function test_entropy_to_mnemonic(Bip39Mnemonic $bip39, BufferInterface $entropy, string $eMnemonic, BufferInterface $eSeed)
    {
        $mnemonic = $bip39->entropyToMnemonic($entropy);
        $this->assertEquals($eMnemonic, $mnemonic);
    }

    /**
     * @dataProvider getBip39Vectors
     */
    public function test_mnemonic_to_entropy(Bip39Mnemonic $bip39, BufferInterface $eEntropy, string $mnemonic, BufferInterface $eSeed)
    {
        $entropy = $bip39->mnemonicToEntropy($mnemonic);
        $this->assertEquals($eEntropy->getBinary(), $entropy->getBinary());
    }

    /**
     * @expectedException \InvalidArgumentException
     *
     * @expectedExceptionMessage Invalid mnemonic
     */
    public function test_incorrect_word_count()
    {
        $bip39 = new Bip39Mnemonic(Bitcoin::getEcAdapter(), new EnglishWordList);
        $mnemonic = 'letter advice';
        $bip39->mnemonicToEntropy($mnemonic);
    }

    /**
     * @expectedException \InvalidArgumentException
     *
     * @expectedExceptionMessage Checksum does not match
     */
    public function test_fails_on_invalid_checksum()
    {
        $bip39 = new Bip39Mnemonic(Bitcoin::getEcAdapter(), new EnglishWordList);
        $mnemonic = 'jelly better achieve collect unaware mountain thought cargo oxygen act hood oxygen';
        $bip39->mnemonicToEntropy($mnemonic);
    }

    /**
     * @expectedException \InvalidArgumentException
     *
     * @expectedExceptionMessage Invalid entropy length
     */
    public function test_fails_on_entropy_mod4()
    {
        $bip39 = new Bip39Mnemonic(Bitcoin::getEcAdapter(), new EnglishWordList);
        $bip39->entropyToMnemonic(Buffer::hex(str_repeat('00', 5)));
    }

    /**
     * @expectedException \InvalidArgumentException
     *
     * @expectedExceptionMessage Invalid entropy length
     */
    public function test_fails_on_entropy_too_long()
    {
        $bip39 = new Bip39Mnemonic(Bitcoin::getEcAdapter(), new EnglishWordList);
        $bip39->entropyToMnemonic(Buffer::hex(str_repeat('00', 1028)));
    }

    /**
     * @expectedException \InvalidArgumentException
     *
     * @expectedExceptionMessage Invalid mnemonic - entropy size is invalid
     */
    public function test_fails_on_mnemonic_of_entropy_too_long()
    {
        $bip39 = new Bip39Mnemonic(Bitcoin::getEcAdapter(), new EnglishWordList);
        $mnemonic = 'abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon about end grace oxygen maze bright face loan ticket trial leg cruel lizard bread worry reject journey perfect chef section caught neither install industry';
        $bip39->mnemonicToEntropy($mnemonic);
    }
}
