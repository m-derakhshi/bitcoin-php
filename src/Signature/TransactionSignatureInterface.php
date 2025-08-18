<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Signature;

use BitWasp\Bitcoin\Crypto\EcAdapter\Signature\SignatureInterface;
use BitWasp\Bitcoin\SerializableInterface;

interface TransactionSignatureInterface extends SerializableInterface
{
    public function getSignature(): SignatureInterface;

    public function getHashType(): int;

    public function equals(TransactionSignatureInterface $other): bool;
}
