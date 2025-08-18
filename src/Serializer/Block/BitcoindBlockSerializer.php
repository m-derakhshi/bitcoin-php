<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Serializer\Block;

use BitWasp\Bitcoin\Block\BlockInterface;
use BitWasp\Bitcoin\Network\NetworkInterface;
use BitWasp\Bitcoin\Serializer\Types;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use BitWasp\Buffertools\Parser;

class BitcoindBlockSerializer
{
    /**
     * @var NetworkInterface
     */
    private $network;

    /**
     * @var BlockSerializer
     */
    private $blockSerializer;

    /**
     * @var \BitWasp\Buffertools\Types\ByteString
     */
    private $magic;

    /**
     * @var \BitWasp\Buffertools\Types\Uint32
     */
    private $size;

    public function __construct(NetworkInterface $network, BlockSerializer $blockSerializer)
    {
        $this->blockSerializer = $blockSerializer;
        $this->magic = Types::bytestringle(4);
        $this->size = Types::uint32le();
        $this->network = $network;
    }

    public function serialize(BlockInterface $block): BufferInterface
    {
        $buffer = $this->blockSerializer->serialize($block);

        return new Buffer(sprintf(
            '%s%s%s',
            strrev(pack('H*', $this->network->getNetMagicBytes())),
            pack('V', $buffer->getSize()),
            $buffer->getBinary()
        ));
    }

    /**
     * @return BlockInterface
     *
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     * @throws \Exception
     */
    public function fromParser(Parser $parser)
    {
        /**
         * @var Buffer $bytes
         * @var int $blockSize
         */
        [$bytes, $blockSize] = [$this->magic->read($parser), (int) $this->size->read($parser)];
        if ($bytes->getHex() !== $this->network->getNetMagicBytes()) {
            throw new \RuntimeException('Block version bytes did not match network');
        }

        return $this->blockSerializer->fromParser(new Parser($parser->readBytes($blockSize)));
    }

    public function parse(BufferInterface $data): BlockInterface
    {
        return $this->fromParser(new Parser($data));
    }
}
