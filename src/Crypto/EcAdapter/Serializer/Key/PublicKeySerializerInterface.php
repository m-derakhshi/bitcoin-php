<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Key;

use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use BitWasp\Buffertools\BufferInterface;

interface PublicKeySerializerInterface
{
    public function serialize(PublicKeyInterface $publicKey): BufferInterface;

    public function parse(BufferInterface $data): PublicKeyInterface;
}
