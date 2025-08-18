<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction;

use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\SerializableInterface;

interface TransactionOutputInterface extends SerializableInterface
{
    /**
     * Get the value of this output
     */
    public function getValue(): int;

    /**
     * Get the script for this output
     */
    public function getScript(): ScriptInterface;

    public function equals(TransactionOutputInterface $output): bool;
}
