<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Script\Branch;

use BitWasp\Bitcoin\Script\Opcodes;
use BitWasp\Bitcoin\Script\Path\BranchInterpreter;
use BitWasp\Bitcoin\Script\Script;
use BitWasp\Bitcoin\Script\ScriptFactory;
use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\Tests\AbstractTestCase;
use BitWasp\Buffertools\Buffer;

class BranchInterpreterTest extends AbstractTestCase
{
    public function test_detects_unexpected_end_if()
    {
        $script = ScriptFactory::sequence([
            Opcodes::OP_ENDIF,
        ]);

        $bi = new BranchInterpreter;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unexpected ENDIF, current scope had no parent');

        $bi->getAstForLogicalOps($script);
    }

    public function test_detects_unexpected_else()
    {
        $script = ScriptFactory::sequence([
            Opcodes::OP_ELSE,
        ]);

        $bi = new BranchInterpreter;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unexpected ELSE, current scope had no parent');

        $bi->getAstForLogicalOps($script);
    }

    public function test_detects_unbalanced_if_branch()
    {
        $script = ScriptFactory::sequence([
            Opcodes::OP_1, Opcodes::OP_IF, Opcodes::OP_DROP,
        ]);

        $bi = new BranchInterpreter;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unbalanced conditional - vfStack not empty at script termination');

        $bi->getAstForLogicalOps($script);
    }

    public function test_detects_reserved_opcodes()
    {
        $script = ScriptFactory::sequence([
            Opcodes::OP_XOR,
        ]);

        $bi = new BranchInterpreter;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Disabled Opcode');

        $bi->evaluateUsingStack($script, []);
    }

    public function test_detects_if_evaluation_without_stack_value()
    {
        $script = ScriptFactory::sequence([
            Opcodes::OP_IF, Opcodes::OP_ENDIF,
        ]);
        $path = [];
        $bi = new BranchInterpreter;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unbalanced conditional at OP_IF - not included in logicalPath');

        $bi->evaluateUsingStack($script, $path);
    }

    public function test_detects_not_if_evaluation_without_stack_value()
    {
        $script = ScriptFactory::sequence([
            Opcodes::OP_NOTIF, Opcodes::OP_ENDIF,
        ]);
        $path = [];
        $bi = new BranchInterpreter;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unbalanced conditional at OP_NOTIF - not included in logicalPath');

        $bi->evaluateUsingStack($script, $path);
    }

    public function test_detects_unbalanced_at_endif()
    {
        $script = ScriptFactory::sequence([
            Opcodes::OP_ENDIF,
        ]);
        $path = [];
        $bi = new BranchInterpreter;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unbalanced conditional at OP_ENDIF');

        $bi->evaluateUsingStack($script, $path);
    }

    public function test_detects_unbalanced_at_else()
    {
        $script = ScriptFactory::sequence([
            Opcodes::OP_ELSE,
        ]);
        $path = [];
        $bi = new BranchInterpreter;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unbalanced conditional at OP_ELSE');

        $bi->evaluateUsingStack($script, $path);
    }

    public function test_detects_unbalanced_unfinished_script()
    {
        $script = ScriptFactory::sequence([
            Opcodes::OP_IF,
        ]);
        $path = [true];
        $bi = new BranchInterpreter;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unbalanced conditional at script end');

        $bi->evaluateUsingStack($script, $path);
    }

    public function test_detects_unbalanced_path()
    {
        $script = ScriptFactory::sequence([
            Opcodes::OP_IF, Opcodes::OP_ENDIF,
        ]);
        $path = [true, true];
        $bi = new BranchInterpreter;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Values remaining after script execution - invalid branch data');

        $bi->evaluateUsingStack($script, $path);
    }

    /**
     * @return array
     */
    public function calcMapOpNames(Opcodes $opcodes)
    {
        $mapOpNames = [];
        for ($op = 0; $op <= Opcodes::OP_NOP10; $op++) {
            if ($op < Opcodes::OP_NOP && $op != Opcodes::OP_RESERVED) {
                continue;
            }

            $name = $opcodes->getOp($op);
            if ($name === 'OP_UNKNOWN') {
                continue;
            }

            $mapOpNames[$name] = $op;
            $mapOpNames[substr($name, 3)] = $op;
        }

        return $mapOpNames;
    }

    /**
     * @return ScriptInterface
     */
    public function calcScriptFromString(array $mapOpNames, string $string)
    {
        $builder = ScriptFactory::create();
        $split = explode(' ', $string);
        foreach ($split as $item) {
            if ($item === 'NOP3') {
                $item = 'OP_CHECKSEQUENCEVERIFY';
            }

            if (strlen($item) == '') {
            } elseif (preg_match('/^[0-9]*$/', $item) || substr($item, 0, 1) === '-' && preg_match('/^[0-9]*$/', substr($item, 1))) {
                $builder->int((int) $item);
            } elseif (substr($item, 0, 2) === '0x') {
                $scriptConcat = new Script(Buffer::hex(substr($item, 2)));
                $builder->concat($scriptConcat);
            } elseif (strlen($item) >= 2 && substr($item, 0, 1) === "'" && substr($item, -1) === "'") {
                $buffer = new Buffer(substr($item, 1, strlen($item) - 2));
                $builder->push($buffer);
            } elseif (isset($mapOpNames[$item])) {
                $builder->sequence([$mapOpNames[$item]]);
            } else {
                throw new \RuntimeException('Script parse error: element "'.$item.'"');
            }
        }

        return $builder->getScript();
    }

    public function getScriptBranchFixtures()
    {
        $opcodes = new Opcodes;
        $mapOps = $this->calcMapOpNames($opcodes);

        $file = $this->dataFile('branch_test.json');
        $sfix = json_decode($file, true)['vectors'];

        foreach ($sfix as &$fixture) {
            $fixture[0] = $this->calcScriptFromString($mapOps, $fixture[0]);
            foreach ($fixture[1] as &$record) {
                $record[1] = $this->calcScriptFromString($mapOps, $record[1]);
                $record[2] = $this->calcScriptFromString($mapOps, $record[2]);
            }
        }

        return $sfix;
    }

    /**
     * @dataProvider getScriptBranchFixtures
     */
    public function test_branch_test(ScriptInterface $script, array $fixtureData)
    {
        $bi = new BranchInterpreter;
        $tree = $bi->getScriptTree($script);

        $this->assertEquals(count($fixtureData), count($tree->getPaths()));
        $this->assertEquals(count($fixtureData) > 1, $tree->hasMultipleBranches());
        foreach ($fixtureData as $fixture) {
            /**
             * @var ScriptInterface $expectedBranch
             */
            [$vfInput, $expectedBranch] = $fixture;

            $foundBranch = false;
            $ub = null;
            foreach ($tree->getPaths() as $path) {
                $branch = $tree->getBranchByPath($path);
                if ($branch->getPath() === $vfInput) {
                    $foundBranch = true;

                    $this->assertTrue($expectedBranch->equals(ScriptFactory::fromOperations($branch->getOps())));
                }
            }

            $this->assertTrue($foundBranch);
        }
    }

    /**
     * @dataProvider getScriptBranchFixtures
     */
    public function test_script_ast(ScriptInterface $script, array $fixtureData)
    {
        $bi = new BranchInterpreter;
        $tree = $bi->getAstForLogicalOps($script);

        $flags = $tree->flags();
        $this->assertEquals(count($fixtureData), count($flags));

        $search = [];
        foreach ($flags as $logicalPath) {
            $search[json_encode($logicalPath)] = 1;
        }

        foreach ($fixtureData as $fixture) {
            $searchKey = json_encode($fixture[0]);
            $this->assertTrue(array_key_exists($searchKey, $search));
        }
    }
}
