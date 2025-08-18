<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Mnemonic;

use BitWasp\Buffertools\BufferInterface;

interface MnemonicInterface
{
    /**
     * @return string[]
     */
    public function entropyToWords(BufferInterface $entropy): array;

    public function entropyToMnemonic(BufferInterface $entropy): string;

    public function mnemonicToEntropy(string $mnemonic): BufferInterface;
}
