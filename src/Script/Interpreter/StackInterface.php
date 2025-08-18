<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Script\Interpreter;

use BitWasp\Buffertools\BufferInterface;

/**
 * @deprecated v2.0.0 Unused in project
 */
interface StackInterface extends \ArrayAccess, \Iterator
{
    /**
     * @see \SplDoublyLinkedList::pop()
     * @return BufferInterface
     */
    public function pop(): BufferInterface;

    /**
     * @see \SplDoublyLinkedList::push()
     * @param BufferInterface $value
     * @return void
     */
    public function push($value);

    /**
     * @see \SplDoublyLinkedList::add()
     * @param int $offset
     * @param BufferInterface $value
     * @return void
     */
    public function add($offset, $value);

    /**
     * @see \SplDoublyLinkedList::bottom()
     * @return BufferInterface
     */
    public function bottom(): BufferInterface;

    /**
     * @see \SplDoublyLinkedList::top()
     * @return BufferInterface
     */
    public function top(): BufferInterface;

    /**
     * @see \SplDoublyLinkedList::isEmpty()
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @see \SplDoublyLinkedList::prev()
     * @return void
     */
    public function prev();

    /**
     * @see \SplDoublyLinkedList::shift()
     * @return BufferInterface
     */
    public function shift(): BufferInterface;

    /**
     * @see \SplDoublyLinkedList::unshift()
     * @param BufferInterface $value
     * @return void
     */
    public function unshift($value);

    /**
     * @see \ArrayAccess::offsetGet()
     * @param int $offset
     * @return BufferInterface
     */
    public function offsetGet($offset): BufferInterface;

    /**
     * @see \ArrayAccess::offsetExists()
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset): bool;

    /**
     * @see \ArrayAccess::offsetUnset()
     * @param int $offset
     * @return void
     */
    public function offsetUnset($offset);

    /**
     * @see \ArrayAccess::offsetSet()
     * @param int $offset
     * @param BufferInterface $value
     * @return void
     */
    public function offsetSet($offset, $value);

    /**
     * Return the current element
     * @see \Iterator::current()
     * @return BufferInterface
     */
    public function current(): BufferInterface;

    /**
     * Move forward to next element
     * @see \Iterator::next()
     * @return void Any returned value is ignored.
     */
    public function next():void;

    /**
     * Return the key of the current element
     * @see \Iterator::key()
     * @return mixed scalar on success, or null on failure.
     */
    public function key();

    /**
     * Checks if current position is valid
     * @see \Iterator::valid()
     * @return boolean The return value will be casted to boolean and then evaluated.
     */
    public function valid(): bool;

    /**
     * Rewind to the first element
     * @see \Iterator::rewind()
     * @return void
     */
    public function rewind():void;

    /**
     * @see \Countable::count()
     * @return int
     */
    public function count(): int;
}