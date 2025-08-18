<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Mnemonic\Bip39\WordList;

use BitWasp\Bitcoin\Tests\AbstractTestCase;

class JapaneseWordListTest extends AbstractTestCase
{
    public function test_get_word_list()
    {
        $wl = new \BitWasp\Bitcoin\Mnemonic\Bip39\Wordlist\JapaneseWordList;
        $this->assertEquals(2048, count($wl));
        $this->assertEquals(2048, count($wl->getWords()));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_unknown_word()
    {
        $wl = new \BitWasp\Bitcoin\Mnemonic\Bip39\Wordlist\JapaneseWordList;
        $wl->getWord(101010101);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_exception_out_of_range()
    {
        $wl = new \BitWasp\Bitcoin\Mnemonic\Bip39\Wordlist\JapaneseWordList;

        $word = $wl->getIndex('あいだ');
        $this->assertInternalType('integer', $word);

        $wl->getIndex('あいあいあい');
    }
}
