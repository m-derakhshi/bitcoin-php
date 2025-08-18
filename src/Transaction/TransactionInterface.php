<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction;

use BitWasp\Bitcoin\Script\ScriptWitnessInterface;
use BitWasp\Bitcoin\SerializableInterface;
use BitWasp\Bitcoin\Utxo\Utxo;
use BitWasp\Buffertools\BufferInterface;

interface TransactionInterface extends SerializableInterface
{
    const DEFAULT_VERSION = 1;

    /**
     * The locktime parameter is encoded as a uint32
     */
    const MAX_LOCKTIME = 4294967295;

    public function isCoinbase(): bool;

    /**
     * Get the transactions sha256d hash.
     */
    public function getTxHash(): BufferInterface;

    /**
     * Get the little-endian sha256d hash.
     */
    public function getTxId(): BufferInterface;

    /**
     * Get the little endian sha256d hash including witness data
     */
    public function getWitnessTxId(): BufferInterface;

    /**
     * Get the version of this transaction
     */
    public function getVersion(): int;

    /**
     * Return an array of all inputs
     *
     * @return TransactionInputInterface[]
     */
    public function getInputs(): array;

    public function getInput(int $index): TransactionInputInterface;

    /**
     * Return an array of all outputs
     *
     * @return TransactionOutputInterface[]
     */
    public function getOutputs(): array;

    public function getOutput(int $vout): TransactionOutputInterface;

    public function getWitness(int $index): ScriptWitnessInterface;

    /**
     * @return ScriptWitnessInterface[]
     */
    public function getWitnesses(): array;

    public function makeOutPoint(int $vout): OutPointInterface;

    public function makeUtxo(int $vout): Utxo;

    /**
     * Return the locktime for this transaction
     */
    public function getLockTime(): int;

    /**
     * @return int
     */
    public function getValueOut();

    public function hasWitness(): bool;

    public function equals(TransactionInterface $tx): bool;

    public function getBaseSerialization(): BufferInterface;

    public function getWitnessSerialization(): BufferInterface;

    /**
     * @deprecated
     */
    public function getWitnessBuffer(): BufferInterface;
}
