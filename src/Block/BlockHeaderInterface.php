<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Block;

use BitWasp\Bitcoin\SerializableInterface;
use BitWasp\Buffertools\BufferInterface;

interface BlockHeaderInterface extends SerializableInterface
{
    /**
     * Return the version of this block.
     */
    public function getVersion(): int;

    /**
     * Return the version of this block.
     */
    public function hasBip9Prefix(): bool;

    /**
     * Return the previous blocks hash.
     */
    public function getPrevBlock(): BufferInterface;

    /**
     * Return the merkle root of the transactions in the block.
     */
    public function getMerkleRoot(): BufferInterface;

    /**
     * Get the timestamp of the block.
     */
    public function getTimestamp(): int;

    /**
     * Return the buffer containing the short representation of the difficulty
     */
    public function getBits(): int;

    /**
     * Return the nonce of the block header.
     */
    public function getNonce(): int;

    /**
     * Return whether this header is equal to the other.
     */
    public function equals(self $header): bool;

    public function getHash(): BufferInterface;
}
