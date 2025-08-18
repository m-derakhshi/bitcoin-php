<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Impl\Secp256k1\Serializer\Key;

use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\Secp256k1\Adapter\EcAdapter;
use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\Secp256k1\Key\PrivateKey;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PrivateKeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Key\PrivateKeySerializerInterface;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;

/**
 * Private Key Serializer - specific to secp256k1
 */
class PrivateKeySerializer implements PrivateKeySerializerInterface
{
    /**
     * @var EcAdapter
     */
    private $ecAdapter;

    public function __construct(EcAdapter $ecAdapter)
    {
        $this->ecAdapter = $ecAdapter;
    }

    private function doSerialize(PrivateKey $privateKey): BufferInterface
    {
        return new Buffer($privateKey->getSecretBinary(), 32);
    }

    public function serialize(PrivateKeyInterface $privateKey): BufferInterface
    {
        /** @var PrivateKey $privateKey */
        return $this->doSerialize($privateKey);
    }

    /**
     * @throws \Exception
     */
    public function fromParser(Parser $parser, bool $compressed): PrivateKeyInterface
    {
        return $this->ecAdapter->getPrivateKey($parser->readBytes(32)->getGmp(), $compressed);
    }

    /**
     * @throws \Exception
     */
    public function parse(BufferInterface $data, bool $compressed): PrivateKeyInterface
    {
        return $this->fromParser(new Parser($data), $compressed);
    }
}
