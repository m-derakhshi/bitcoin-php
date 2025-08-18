<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Chain;

use BitWasp\Bitcoin\Block\BlockHeaderInterface;
use BitWasp\Bitcoin\Block\BlockInterface;

interface ParamsInterface
{
    public function getGenesisBlockHeader(): BlockHeaderInterface;

    public function getGenesisBlock(): BlockInterface;

    public function maxBlockSizeBytes(): int;

    public function subsidyHalvingInterval(): int;

    public function coinbaseMaturityAge(): int;

    public function maxMoney(): int;

    public function powTargetTimespan(): int;

    public function powTargetSpacing(): int;

    public function powRetargetInterval(): int;

    public function powTargetLimit(): string;

    public function powBitsLimit(): int;

    public function majorityEnforceBlockUpgrade(): int;

    public function majorityWindow(): int;

    public function p2shActivateTime(): int;

    public function getMaxBlockSigOps(): int;

    public function getMaxTxSigOps(): int;
}
