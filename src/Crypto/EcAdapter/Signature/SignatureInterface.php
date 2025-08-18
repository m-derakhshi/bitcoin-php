<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Signature;

use BitWasp\Bitcoin\SerializableInterface;

interface SignatureInterface extends SerializableInterface
{
    public function equals(SignatureInterface $signature): bool;
}
