<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Serializer\Transaction;

use BitWasp\Bitcoin\Transaction\TransactionInterface;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;

interface TransactionSerializerInterface
{
    public function fromParser(Parser $parser): TransactionInterface;

    public function parse(BufferInterface $data): TransactionInterface;

    public function serialize(TransactionInterface $transaction, int $optFlags = 0): BufferInterface;
}
