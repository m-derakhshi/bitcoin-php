<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction\Factory;

use BitWasp\Bitcoin\Script\Opcodes;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class Conditional
{
    /**
     * @var int
     */
    private $opcode;

    /**
     * @var bool
     */
    private $value;

    /**
     * @var null
     */
    private $providedBy = null;

    /**
     * Conditional constructor.
     */
    public function __construct(int $opcode)
    {
        if ($opcode !== Opcodes::OP_IF && $opcode !== Opcodes::OP_NOTIF) {
            throw new \RuntimeException('Opcode for conditional is only IF / NOTIF');
        }

        $this->opcode = $opcode;
    }

    public function getOp(): int
    {
        return $this->opcode;
    }

    public function setValue(bool $value)
    {
        $this->value = $value;
    }

    public function hasValue(): bool
    {
        return $this->value !== null;
    }

    public function getValue(): bool
    {
        if ($this->value === null) {
            throw new \RuntimeException('Value not set on conditional');
        }

        return $this->value;
    }

    public function providedBy(Checksig $checksig)
    {
        $this->providedBy = $checksig;
    }

    /**
     * @return BufferInterface[]
     */
    public function serialize(): array
    {
        if ($this->hasValue() && $this->providedBy === null) {
            return [$this->value ? new Buffer("\x01") : new Buffer];
        }

        return [];
    }
}
