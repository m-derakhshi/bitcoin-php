<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin;

use BitWasp\Buffertools\BufferInterface;

interface SerializableInterface extends \BitWasp\Buffertools\SerializableInterface
{
    public function getBuffer(): BufferInterface;

    public function getHex(): string;

    public function getBinary(): string;

    /**
     * @return string
     */
    public function getInt();
}
