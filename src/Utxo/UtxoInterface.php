<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Utxo;

use BitWasp\Bitcoin\Transaction\OutPointInterface;
use BitWasp\Bitcoin\Transaction\TransactionOutputInterface;

interface UtxoInterface
{
    public function getOutPoint(): OutPointInterface;

    public function getOutput(): TransactionOutputInterface;
}
