<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Serializer\Key;

use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Adapter\EcAdapter;
use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Key\PublicKey;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class PublicKeySerializer implements PublicKeySerializerInterface
{
    /**
     * @var EcAdapter
     */
    private $ecAdapter;

    public function __construct(EcAdapter $ecAdapter)
    {
        $this->ecAdapter = $ecAdapter;
    }

    public function getPrefix(PublicKey $publicKey): string
    {
        if ($publicKey->getPrefix() === null) {
            return $publicKey->isCompressed()
                ? $this->ecAdapter->getMath()->isEven($publicKey->getPoint()->getY())
                    ? PublicKey::KEY_COMPRESSED_EVEN
                    : PublicKey::KEY_COMPRESSED_ODD
                : PublicKey::KEY_UNCOMPRESSED;
        } else {
            return $publicKey->getPrefix();
        }
    }

    private function doSerialize(PublicKey $publicKey): BufferInterface
    {
        $point = $publicKey->getPoint();

        $length = 33;
        $data = $this->getPrefix($publicKey).Buffer::int(gmp_strval($point->getX(), 10), 32)->getBinary();
        if (! $publicKey->isCompressed()) {
            $length = 65;
            $data .= Buffer::int(gmp_strval($point->getY(), 10), 32)->getBinary();
        }

        return new Buffer($data, $length);
    }

    public function serialize(PublicKeyInterface $publicKey): BufferInterface
    {
        /** @var PublicKey $publicKey */
        return $this->doSerialize($publicKey);
    }

    /**
     * @throws \Exception
     */
    public function parse(BufferInterface $buffer): PublicKeyInterface
    {
        if (! in_array($buffer->getSize(), [PublicKey::LENGTH_COMPRESSED, PublicKey::LENGTH_UNCOMPRESSED], true)) {
            throw new \Exception('Invalid hex string, must match size of compressed or uncompressed public key');
        }

        /** @var PublicKey $key */
        $key = $this->ecAdapter->publicKeyFromBuffer($buffer);

        return $key;
    }
}
