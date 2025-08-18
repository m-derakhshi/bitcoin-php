<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Mnemonic\Electrum\WordList;

use BitWasp\Bitcoin\Mnemonic\Electrum\Wordlist\EnglishWordList;
use BitWasp\Bitcoin\Tests\AbstractTestCase;

class EnglishWordListTest extends AbstractTestCase
{
    public function test_get_word_list()
    {
        $wl = new EnglishWordList;
        $this->assertEquals(1626, count($wl));
        $this->assertEquals(1626, count($wl->getWords()));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_unknown_word()
    {
        $wl = new EnglishWordList;
        $wl->getWord(101010101);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_exception_out_of_range()
    {
        $wl = new EnglishWordList;

        $word = $wl->getIndex('just');
        $this->assertInternalType('integer', $word);

        $wl->getIndex('unknownword');
    }
}
