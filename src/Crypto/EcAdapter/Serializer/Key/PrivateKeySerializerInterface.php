<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Key;

use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PrivateKeyInterface;
use BitWasp\Buffertools\BufferInterface;

interface PrivateKeySerializerInterface
{
    public function serialize(PrivateKeyInterface $privateKey): BufferInterface;

    public function parse(BufferInterface $data, bool $compressed): PrivateKeyInterface;
}
