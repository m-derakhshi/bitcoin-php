<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Script\Branch;

use BitWasp\Bitcoin\Script\Path\LogicOpNode;
use BitWasp\Bitcoin\Tests\AbstractTestCase;

class LogicOpNodeTest extends AbstractTestCase
{
    public function test_get_child_with_none_throws_error()
    {
        $logicNode = new LogicOpNode;
        $this->assertFalse($logicNode->hasChildren());
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Child not found');

        $logicNode->getChild(0);
    }

    public function test_node_wont_split_twice()
    {
        $logicNode = new LogicOpNode;
        $this->assertFalse($logicNode->hasChildren());
        $logicNode->split();

        $this->assertTrue($logicNode->hasChildren());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Sanity check - don't split twice");

        $logicNode->split();
    }
}
