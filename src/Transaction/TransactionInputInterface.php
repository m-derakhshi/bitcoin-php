<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction;

use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\SerializableInterface;

interface TransactionInputInterface extends SerializableInterface
{
    /**
     * The default sequence.
     */
    const SEQUENCE_FINAL = 0xFFFFFFFF;

    /* If this flag set, CTxIn::nSequence is NOT interpreted as a
     * relative lock-time. */
    const SEQUENCE_LOCKTIME_DISABLE_FLAG = 1 << 31; // 1 << 31

    /* If CTxIn::nSequence encodes a relative lock-time and this flag
     * is set, the relative lock-time has units of 512 seconds,
     * otherwise it specifies blocks with a granularity of 1. */
    const SEQUENCE_LOCKTIME_TYPE_FLAG = 1 << 22; // 1 << 22;

    /* If CTxIn::nSequence encodes a relative lock-time, this mask is
     * applied to extract that lock-time from the sequence field. */
    const SEQUENCE_LOCKTIME_MASK = 0x0000FFFF;

    /**
     * Get the outpoint
     */
    public function getOutPoint(): OutPointInterface;

    /**
     * Get the scriptSig for this input
     */
    public function getScript(): ScriptInterface;

    /**
     * Get the sequence number for this input
     */
    public function getSequence(): int;

    /**
     * Equality check with $this and $other
     */
    public function equals(TransactionInputInterface $other): bool;

    /**
     * Check whether the transaction is the Coinbase, ie, it has
     * one input which spends the `null` outpoint
     */
    public function isCoinBase(): bool;

    public function isFinal(): bool;

    /**
     * Checks whether the SEQUENCE_LOCKTIME_DISABLE_FLAG is set
     * Always returns true if txin is coinbase.
     */
    public function isSequenceLockDisabled(): bool;

    /**
     * Indicates whether the input is locked with a time based lock (as opposed to block)
     */
    public function isLockedToTime(): bool;

    /**
     * Returns whether the input is locked with a block based lock (as opposed to time)
     */
    public function isLockedToBlock(): bool;

    /**
     * Returns the relative block time for the input.
     * Range limited to 0 - 33553920 (approx 1 yr)
     */
    public function getRelativeBlockLock(): int;

    /**
     * Returns the relative locktime for the input in seconds.
     * Range limited to 0 - 65535
     */
    public function getRelativeTimeLock(): int;
}
