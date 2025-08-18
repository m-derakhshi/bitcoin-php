<?php

namespace BitWasp\Bitcoin\Script\Consensus;

use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\Transaction\TransactionInterface;

interface ConsensusInterface
{
    public function verify(TransactionInterface $tx, ScriptInterface $scriptPubKey, int $flags, int $nInputToSign, int $amount): bool;
}
