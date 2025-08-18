<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Key;

use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use BitWasp\Bitcoin\SerializableInterface;
use BitWasp\Buffertools\BufferInterface;

interface KeyInterface extends SerializableInterface
{
    /**
     * Check if the key should be be using compressed format
     */
    public function isCompressed(): bool;

    /**
     * Return a boolean indicating whether the key is private.
     */
    public function isPrivate(): bool;

    /**
     * Return the hash of the public key.
     */
    public function getPubKeyHash(?PublicKeySerializerInterface $serializer = null): BufferInterface;

    public function tweakAdd(\GMP $offset): KeyInterface;

    public function tweakMul(\GMP $offset): KeyInterface;

    public function getBuffer(): BufferInterface;
}
