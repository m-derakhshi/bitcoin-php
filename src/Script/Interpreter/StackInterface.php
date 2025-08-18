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
     */
    public function pop(): BufferInterface;

    /**
     * @see \SplDoublyLinkedList::push()
     *
     * @param  BufferInterface  $value
     * @return void
     */
    public function push($value);

    /**
     * @see \SplDoublyLinkedList::add()
     *
     * @param  int  $offset
     * @param  BufferInterface  $value
     * @return void
     */
    public function add($offset, $value);

    /**
     * @see \SplDoublyLinkedList::bottom()
     */
    public function bottom(): BufferInterface;

    /**
     * @see \SplDoublyLinkedList::top()
     */
    public function top(): BufferInterface;

    /**
     * @see \SplDoublyLinkedList::isEmpty()
     */
    public function isEmpty(): bool;

    /**
     * @see \SplDoublyLinkedList::prev()
     *
     * @return void
     */
    public function prev();

    /**
     * @see \SplDoublyLinkedList::shift()
     */
    public function shift(): BufferInterface;

    /**
     * @see \SplDoublyLinkedList::unshift()
     *
     * @param  BufferInterface  $value
     * @return void
     */
    public function unshift($value);

    /**
     * @see \ArrayAccess::offsetGet()
     *
     * @param  int  $offset
     */
    public function offsetGet($offset): BufferInterface;

    /**
     * @see \ArrayAccess::offsetExists()
     *
     * @param  int  $offset
     */
    public function offsetExists($offset): bool;

    /**
     * @see \ArrayAccess::offsetUnset()
     *
     * @param  int  $offset
     * @return void
     */
    public function offsetUnset($offset);

    /**
     * @see \ArrayAccess::offsetSet()
     *
     * @param  int  $offset
     * @param  BufferInterface  $value
     * @return void
     */
    public function offsetSet($offset, $value);

    /**
     * Return the current element
     *
     * @see \Iterator::current()
     */
    public function current(): BufferInterface;

    /**
     * Move forward to next element
     *
     * @see \Iterator::next()
     *
     * @return void Any returned value is ignored.
     */
    public function next(): void;

    /**
     * Return the key of the current element
     *
     * @see \Iterator::key()
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key();

    /**
     * Checks if current position is valid
     *
     * @see \Iterator::valid()
     *
     * @return bool The return value will be casted to boolean and then evaluated.
     */
    public function valid(): bool;

    /**
     * Rewind to the first element
     *
     * @see \Iterator::rewind()
     */
    public function rewind(): void;

    /**
     * @see \Countable::count()
     */
    public function count(): int;
}
