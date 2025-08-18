<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction;

use BitWasp\Bitcoin\SerializableInterface;
use BitWasp\Buffertools\BufferInterface;

interface OutPointInterface extends SerializableInterface
{
    public function getTxId(): BufferInterface;

    public function getVout(): int;

    public function equals(OutPointInterface $outPoint): bool;
}
