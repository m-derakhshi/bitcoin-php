<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin;

abstract class Serializable implements SerializableInterface
{
    public function getHex(): string
    {
        return $this->getBuffer()->getHex();
    }

    public function getBinary(): string
    {
        return $this->getBuffer()->getBinary();
    }

    /**
     * @return int
     */
    public function getInt()
    {
        return $this->getBuffer()->getInt();
    }
}
