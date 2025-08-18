<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Serializer\Transaction;

use BitWasp\Bitcoin\Transaction\OutPointInterface;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;

interface OutPointSerializerInterface
{
    public function serialize(OutPointInterface $outpoint): BufferInterface;

    public function fromParser(Parser $parser): OutPointInterface;

    public function parse(BufferInterface $data): OutPointInterface;
}
