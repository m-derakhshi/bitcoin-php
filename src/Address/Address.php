<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Address;

use BitWasp\Buffertools\BufferInterface;

/**
 * Abstract Class Address
 * Used to store a hash
 */
abstract class Address implements AddressInterface
{
    /**
     * @var BufferInterface
     */
    protected $hash;

    public function __construct(BufferInterface $hash)
    {
        $this->hash = $hash;
    }

    public function getHash(): BufferInterface
    {
        return $this->hash;
    }
}
