<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction\Mutator;

abstract class AbstractCollectionMutator implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * @var \SplFixedArray
     */
    protected $set;

    public function all(): array
    {
        return $this->set->toArray();
    }

    public function isNull(): bool
    {
        return count($this->set) === 0;
    }

    public function count(): int
    {
        return $this->set->count();
    }

    public function rewind(): void
    {
        if ($this->set instanceof \ArrayIterator) {
            $this->set->rewind();
        } elseif ($this->set instanceof \SplFixedArray) {
            $array = $this->set->toArray();
            $this->set = new \ArrayIterator($array);
            $this->set->rewind();
        } else {
            throw new \LogicException('Unsupported collection type for rewind');
        }
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->set->current();
    }

    public function key(): int
    {
        return $this->set->key();
    }

    public function next(): void
    {
        $this->set->next();
    }

    public function valid(): bool
    {
        return $this->set->valid();
    }

    /**
     * @param  int  $offset
     */
    public function offsetExists($offset): bool
    {
        return $this->set->offsetExists($offset);
    }

    /**
     * @param  int  $offset
     */
    public function offsetUnset($offset)
    {
        if (! $this->offsetExists($offset)) {
            throw new \InvalidArgumentException('Offset does not exist');
        }

        $this->set->offsetUnset($offset);
    }

    /**
     * @param  int  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (! $this->set->offsetExists($offset)) {
            throw new \OutOfRangeException('Nothing found at this offset');
        }

        return $this->set->offsetGet($offset);
    }

    /**
     * @param  int  $offset
     * @param  mixed  $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set->offsetSet($offset, $value);
    }
}
