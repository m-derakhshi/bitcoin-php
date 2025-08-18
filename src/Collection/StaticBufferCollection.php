<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Collection;

use BitWasp\Buffertools\BufferInterface;

/**
 * @deprecated v2.0.0
 */
class StaticBufferCollection extends StaticCollection
{
    /**
     * @var BufferInterface[]
     */
    protected $set = [];

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * StaticBufferCollection constructor.
     */
    public function __construct(BufferInterface ...$values)
    {
        $this->set = $values;
    }

    public function bottom(): BufferInterface
    {
        return parent::bottom();
    }

    public function top(): BufferInterface
    {
        return parent::top();
    }

    public function current(): BufferInterface
    {
        return $this->set[$this->position];
    }

    /**
     * @param  int  $offset
     * @return BufferInterface
     */
    public function offsetGet($offset)
    {
        if (! array_key_exists($offset, $this->set)) {
            throw new \OutOfRangeException('No offset found');
        }

        return $this->set[$offset];
    }
}
