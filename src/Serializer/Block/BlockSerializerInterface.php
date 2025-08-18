<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Serializer\Block;

use BitWasp\Bitcoin\Block\BlockInterface;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Exceptions\ParserOutOfRange;
use BitWasp\Buffertools\Parser;

interface BlockSerializerInterface
{
    /**
     * @throws ParserOutOfRange
     */
    public function fromParser(Parser $parser): BlockInterface;

    /**
     * @throws ParserOutOfRange
     */
    public function parse(BufferInterface $buffer): BlockInterface;

    public function serialize(BlockInterface $block): BufferInterface;
}
