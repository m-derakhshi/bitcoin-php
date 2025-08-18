<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin;

class Amount
{
    const COIN = 100000000;

    public function toBtc(int $satoshis): string
    {
        return bcdiv((string) $satoshis, (string) self::COIN, 8);
    }

    public function toSatoshis(string $btcAmount): int
    {
        return (int) bcmul($btcAmount, (string) self::COIN, 0);
    }
}
