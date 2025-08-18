<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Script;

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use BitWasp\Bitcoin\Math\Math;
use BitWasp\Bitcoin\Script\Consensus\BitcoinConsensus;
use BitWasp\Bitcoin\Script\Consensus\ConsensusInterface;
use BitWasp\Bitcoin\Script\Consensus\NativeConsensus;
use BitWasp\Bitcoin\Script\Factory\OutputScriptFactory;
use BitWasp\Bitcoin\Script\Factory\ScriptCreator;
use BitWasp\Bitcoin\Script\Parser\Operation;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class ScriptFactory
{
    /**
     * @var OutputScriptFactory
     */
    private static $outputScriptFactory = null;

    /**
     * @throws \Exception
     */
    public static function fromHex(string $string): ScriptInterface
    {
        return self::fromBuffer(Buffer::hex($string));
    }

    public static function fromBuffer(BufferInterface $buffer, ?Opcodes $opcodes = null, ?Math $math = null): ScriptInterface
    {
        return self::create($buffer, $opcodes, $math)->getScript();
    }

    public static function create(?BufferInterface $buffer = null, ?Opcodes $opcodes = null, ?Math $math = null): ScriptCreator
    {
        return new ScriptCreator($math ?: Bitcoin::getMath(), $opcodes ?: new Opcodes, $buffer);
    }

    /**
     * Create a script consisting only of push-data operations.
     * Suitable for a scriptSig.
     *
     * @param  BufferInterface[]  $buffers
     */
    public static function pushAll(array $buffers): ScriptInterface
    {
        return self::sequence(array_map(function ($buffer) {
            if (! ($buffer instanceof BufferInterface)) {
                throw new \RuntimeException('Script contained a non-push opcode');
            }

            $size = $buffer->getSize();
            if ($size === 0) {
                return Opcodes::OP_0;
            }

            $first = ord($buffer->getBinary()[0]);
            if ($size === 1 && $first >= 1 && $first <= 16) {
                return \BitWasp\Bitcoin\Script\encodeOpN($first);
            } else {
                return $buffer;
            }
        }, $buffers));
    }

    /**
     * @param  int[]|\BitWasp\Bitcoin\Script\Interpreter\Number[]|BufferInterface[]  $sequence
     */
    public static function sequence(array $sequence): ScriptInterface
    {
        return self::create()->sequence($sequence)->getScript();
    }

    /**
     * @param  Operation[]  $operations
     */
    public static function fromOperations(array $operations): ScriptInterface
    {
        $sequence = [];
        foreach ($operations as $operation) {
            if (! ($operation instanceof Operation)) {
                throw new \RuntimeException('Invalid input to fromOperations');
            }

            $sequence[] = $operation->encode();
        }

        return self::sequence($sequence);
    }

    public static function scriptPubKey(): OutputScriptFactory
    {
        if (self::$outputScriptFactory === null) {
            self::$outputScriptFactory = new OutputScriptFactory;
        }

        return self::$outputScriptFactory;
    }

    public static function getNativeConsensus(?EcAdapterInterface $ecAdapter = null): NativeConsensus
    {
        return new NativeConsensus($ecAdapter ?: Bitcoin::getEcAdapter());
    }

    public static function getBitcoinConsensus(): BitcoinConsensus
    {
        return new BitcoinConsensus;
    }

    public static function consensus(?EcAdapterInterface $ecAdapter = null): ConsensusInterface
    {
        if (extension_loaded('bitcoinconsensus')) {
            return self::getBitcoinConsensus();
        } else {
            return self::getNativeConsensus($ecAdapter);
        }
    }
}
