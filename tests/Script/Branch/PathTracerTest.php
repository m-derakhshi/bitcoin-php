<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Script\Branch;

use BitWasp\Bitcoin\Script\Opcodes;
use BitWasp\Bitcoin\Script\Parser\Operation;
use BitWasp\Bitcoin\Script\Path\PathTracer;
use BitWasp\Bitcoin\Tests\AbstractTestCase;
use BitWasp\Buffertools\Buffer;

class PathTracerTest extends AbstractTestCase
{
    public function test_trace_no_operations()
    {
        $tracer = new PathTracer;
        $result = $tracer->done();

        $this->assertInternalType('array', $result);
        $this->assertEquals(0, count($result));

        $resultAgain = $tracer->done();
        $this->assertSame($result, $resultAgain);
    }

    public function test_trace_just_one_operation()
    {
        $op0 = new Operation(Opcodes::OP_0, new Buffer);

        $tracer = new PathTracer;
        $tracer->operation($op0);

        $result = $tracer->done();

        $this->assertInternalType('array', $result);
        $this->assertEquals(1, count($result));

        $resultAgain = $tracer->done();
        $this->assertSame($result, $resultAgain);

        $op1 = new Operation(Opcodes::OP_1, new Buffer("\x01"));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot add operation to finished PathTracer');

        $tracer->operation($op1);
    }
}
