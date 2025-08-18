<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Key;

use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use BitWasp\Bitcoin\Crypto\Hash;
use BitWasp\Bitcoin\Serializable;
use BitWasp\Buffertools\BufferInterface;

abstract class Key extends Serializable implements KeyInterface
{
    /**
     * @var BufferInterface
     */
    protected $pubKeyHash;

    public function isPrivate(): bool
    {
        return $this instanceof PrivateKeyInterface;
    }

    public function getPubKeyHash(?PublicKeySerializerInterface $serializer = null): BufferInterface
    {
        if ($this instanceof PrivateKeyInterface) {
            $publicKey = $this->getPublicKey();
        } else {
            $publicKey = $this;
        }

        if ($this->pubKeyHash === null) {
            $this->pubKeyHash = Hash::sha256ripe160($serializer ? $serializer->serialize($publicKey) : $publicKey->getBuffer());
        }

        return $this->pubKeyHash;
    }
}
