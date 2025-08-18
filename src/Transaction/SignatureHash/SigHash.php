<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction\SignatureHash;

use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\Transaction\TransactionInterface;
use BitWasp\Buffertools\BufferInterface;

abstract class SigHash implements SigHashInterface
{
    const V0 = 0;

    const V1 = 1;

    /**
     * @var TransactionInterface
     */
    protected $tx;

    /**
     * SigHash constructor.
     */
    public function __construct(TransactionInterface $transaction)
    {
        $this->tx = $transaction;
    }

    abstract public function calculate(
        ScriptInterface $txOutScript,
        int $inputToSign,
        int $sighashType = self::ALL
    ): BufferInterface;
}
