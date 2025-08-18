<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Mnemonic;

interface WordListInterface extends \Countable
{
    /**
     * @return string[]
     */
    public function getWords(): array;

    public function getWord(int $index): string;

    public function getIndex(string $word): int;
}
