<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Block;

use BitWasp\Bitcoin\Bloom\BloomFilter;
use BitWasp\Bitcoin\SerializableInterface;
use BitWasp\Bitcoin\Transaction\TransactionInterface;
use BitWasp\Buffertools\BufferInterface;

interface BlockInterface extends SerializableInterface
{
    const MAX_BLOCK_SIZE = 1000000;

    /**
     * Get the header of this block.
     */
    public function getHeader(): BlockHeaderInterface;

    /**
     * Calculate the merkle root of the transactions in the block.
     */
    public function getMerkleRoot(): BufferInterface;

    /**
     * Return the block's transactions.
     *
     * @return TransactionInterface[]
     */
    public function getTransactions(): array;

    public function getTransaction(int $i): TransactionInterface;

    public function filter(BloomFilter $filter): FilteredBlock;
}
