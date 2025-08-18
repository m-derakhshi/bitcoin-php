<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Transaction;

use BitWasp\Bitcoin\Script\Script;
use BitWasp\Bitcoin\Serializer\Transaction\TransactionOutputSerializer;
use BitWasp\Bitcoin\Tests\AbstractTestCase;
use BitWasp\Bitcoin\Transaction\TransactionOutput;
use BitWasp\Bitcoin\Transaction\TransactionOutputInterface;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\Parser;

class TransactionOutputTest extends AbstractTestCase
{
    public function test_get_value_default()
    {
        $out = new TransactionOutput(1, new Script);
        $this->assertSame(1, $out->getValue());

        $out = new TransactionOutput(10901, new Script);
        $this->assertSame(10901, $out->getValue());
    }

    public function test_get_script()
    {
        $testScript = new Script(Buffer::hex('414141'));
        $out = new TransactionOutput(1, $testScript);
        $script = $out->getScript();
        $this->assertInstanceOf(Script::class, $script);
    }

    public function test_from_parser()
    {
        $buffer = Buffer::hex('cac10000000000001976a9140eff868646ece0af8bc979093585e80297112f1f88ac');
        $parser = new Parser($buffer);
        $s = new TransactionOutputSerializer;
        $out = $s->fromParser($parser);
        $this->assertInstanceOf(TransactionOutputInterface::class, $out);
    }

    public function test_equals()
    {
        $o = new TransactionOutput(1, new Script);
        $oEq = new TransactionOutput(1, new Script);
        $oBadVal = new TransactionOutput(100, new Script);
        $oBadScript = new TransactionOutput(1, new Script(new Buffer('a')));

        $this->assertTrue($o->equals($oEq));
        $this->assertFalse($o->equals($oBadVal));
        $this->assertFalse($o->equals($oBadScript));
    }

    public function test_serialize()
    {
        $buffer = Buffer::hex('cac10000000000001976a9140eff868646ece0af8bc979093585e80297112f1f88ac');
        $s = new TransactionOutputSerializer;
        $out = $s->parse($buffer);
        $this->assertTrue($buffer->equals($out->getBuffer()));
    }
}
