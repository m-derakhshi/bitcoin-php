<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Script;

use BitWasp\Bitcoin\Script\Opcodes;
use BitWasp\Bitcoin\Tests\AbstractTestCase;

class OpcodesTest extends AbstractTestCase
{
    public function test_get_op_by_name()
    {
        $op = new Opcodes;
        $expected = 0;
        $lookupOpName = 'OP_0';
        $val = $op->getOpByName('OP_0');
        $this->assertSame($expected, $val);
        $this->assertTrue(isset($op[Opcodes::OP_0]));
        $this->assertSame($lookupOpName, $op[Opcodes::OP_0]);
    }

    /**
     * @expectedException \RuntimeException
     *
     * @expectedExceptionMessage Opcode by that name not found
     */
    public function test_get_op_by_name_fail()
    {
        $op = new Opcodes;
        $op->getOpByName('OP_DEADBEEF');
    }

    public function test_get_op()
    {
        $op = new Opcodes;
        // Check getRegisteredOpCode returns the right operation
        $expected = 'OP_0';
        $val = $op->getOp(0);

        $this->assertSame($expected, $val);
    }

    /**
     * @expectedException \RuntimeException
     *
     * @expectedExceptionMessage Opcode not found
     */
    public function test_get_op_code_exception()
    {
        $op = new Opcodes;
        $op->getOp(3);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_no_write_set()
    {
        $op = new Opcodes;
        $op[1] = 2;
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_no_write_un_set()
    {
        $op = new Opcodes;
        unset($op[Opcodes::OP_1]);
    }

    public function test_debug_info()
    {
        $op = new Opcodes;
        $this->assertEquals([], $op->__debugInfo());
    }
}
