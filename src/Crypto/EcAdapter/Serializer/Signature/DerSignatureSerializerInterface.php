<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Signature;

use BitWasp\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Signature\SignatureInterface;
use BitWasp\Buffertools\BufferInterface;

interface DerSignatureSerializerInterface
{
    /**
     * @return EcAdapterInterface
     */
    public function getEcAdapter();

    public function serialize(SignatureInterface $signature): BufferInterface;

    public function parse(BufferInterface $derSignature): SignatureInterface;
}
