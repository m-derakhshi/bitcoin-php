<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Script;

use BitWasp\Bitcoin\Script\Parser\Parser;
use BitWasp\Bitcoin\SerializableInterface;
use BitWasp\Buffertools\BufferInterface;

interface ScriptInterface extends SerializableInterface
{
    public function getScriptHash(): BufferInterface;

    public function getWitnessScriptHash(): BufferInterface;

    public function getScriptParser(): Parser;

    public function getOpcodes(): Opcodes;

    /**
     * Returns boolean indicating whether script
     * was push only. If true, $ops is populated
     * with the contained buffers
     */
    public function isPushOnly(?array &$ops = null): bool;

    /**
     * @param  WitnessProgram|null  $witness
     */
    public function isWitness(&$witness): bool;

    /**
     * @param  BufferInterface  $scriptHash
     */
    public function isP2SH(&$scriptHash): bool;

    public function countSigOps(bool $accurate = true): int;

    public function countP2shSigOps(ScriptInterface $scriptSig): int;

    public function countWitnessSigOps(ScriptInterface $scriptSig, ScriptWitnessInterface $witness, int $flags): int;

    public function equals(ScriptInterface $script): bool;
}
