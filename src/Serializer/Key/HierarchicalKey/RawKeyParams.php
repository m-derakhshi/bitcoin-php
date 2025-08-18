<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Serializer\Key\HierarchicalKey;

use BitWasp\Buffertools\BufferInterface;

class RawKeyParams
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var int
     */
    private $depth;

    /**
     * @var int
     */
    private $parentFpr;

    /**
     * @var int
     */
    private $sequence;

    /**
     * @var BufferInterface
     */
    private $chainCode;

    /**
     * @var BufferInterface
     */
    private $keyData;

    /**
     * RawKeyParams constructor.
     */
    public function __construct(string $prefix, int $depth, int $parentFingerprint, int $sequence, BufferInterface $chainCode, BufferInterface $keyData)
    {
        $this->prefix = $prefix;
        $this->depth = $depth;
        $this->parentFpr = $parentFingerprint;
        $this->sequence = $sequence;
        $this->chainCode = $chainCode;
        $this->keyData = $keyData;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function getParentFingerprint(): int
    {
        return $this->parentFpr;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }

    public function getChainCode(): BufferInterface
    {
        return $this->chainCode;
    }

    public function getKeyData(): BufferInterface
    {
        return $this->keyData;
    }
}
