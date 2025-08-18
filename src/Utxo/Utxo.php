<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Utxo;

use BitWasp\Bitcoin\Transaction\OutPointInterface;
use BitWasp\Bitcoin\Transaction\TransactionOutputInterface;

class Utxo implements UtxoInterface
{
    /**
     * @var OutPointInterface
     */
    private $outPoint;

    /**
     * @var TransactionOutputInterface
     */
    private $prevOut;

    public function __construct(OutPointInterface $outPoint, TransactionOutputInterface $prevOut)
    {
        $this->outPoint = $outPoint;
        $this->prevOut = $prevOut;
    }

    public function getOutPoint(): OutPointInterface
    {
        return $this->outPoint;
    }

    public function getOutput(): TransactionOutputInterface
    {
        return $this->prevOut;
    }
}
