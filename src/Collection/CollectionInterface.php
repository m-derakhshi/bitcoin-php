<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Collection;

/**
 * @deprecated v2.0.0
 */
interface CollectionInterface extends \ArrayAccess, \Countable, \Iterator
{
    public function all(): array;

    /**
     * @return mixed
     */
    public function bottom();

    /**
     * @return mixed
     */
    public function top();

    /**
     * @return self
     */
    public function slice(int $start, int $length);

    public function isNull(): bool;
}
