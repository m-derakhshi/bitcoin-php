<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Collection;

use BitWasp\Buffertools\BufferInterface;

/**
 * @deprecated v2.0.0
 */
abstract class StaticCollection
{
    /**
     * @var array
     */
    protected $set;

    /**
     * @var int
     */
    protected $position = 0;

    public function all(): array
    {
        return $this->set;
    }

    /**
     * @return self
     */
    public function slice(int $start, int $length)
    {
        $end = count($this->set);
        if ($start > $end || $length > $end) {
            throw new \RuntimeException('Invalid start or length');
        }

        $sliced = array_slice($this->set, $start, $length);

        return new static(...$sliced);
    }

    public function count(): int
    {
        return count($this->set);
    }

    public function bottom(): BufferInterface
    {
        if (count($this->set) === 0) {
            throw new \RuntimeException('No bottom for empty collection');
        }

        return $this->offsetGet(count($this) - 1);
    }

    public function top(): BufferInterface
    {
        if (count($this->set) === 0) {
            throw new \RuntimeException('No top for empty collection');
        }

        return $this->offsetGet(0);
    }

    public function isNull(): bool
    {
        return count($this->set) === 0;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): BufferInterface
    {
        return $this->set[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function valid(): bool
    {
        return isset($this->set[$this->position]);
    }

    /**
     * @param  int  $offset
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->set);
    }

    /**
     * @param  int  $offset
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Cannot unset from a Static Collection');
    }

    /**
     * @param  int  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (! array_key_exists($offset, $this->set)) {
            throw new \OutOfRangeException('Nothing found at this offset');
        }

        return $this->set[$offset];
    }

    /**
     * @param  int  $offset
     * @param  mixed  $value
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Cannot add to a Static Collection');
    }
}
