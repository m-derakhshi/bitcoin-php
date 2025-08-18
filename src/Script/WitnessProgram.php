<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Script;

use BitWasp\Buffertools\BufferInterface;

class WitnessProgram
{
    const V0 = 0;

    /**
     * @var int
     */
    private $version;

    /**
     * @var BufferInterface
     */
    private $program;

    /**
     * @var ScriptInterface|null
     */
    private $outputScript;

    /**
     * WitnessProgram constructor.
     */
    public function __construct(int $version, BufferInterface $program)
    {
        if ($this->version < 0 || $this->version > 16) {
            throw new \RuntimeException('Invalid witness program version');
        }

        if ($this->version === 0 && ($program->getSize() !== 20 && $program->getSize() !== 32)) {
            throw new \RuntimeException('Invalid size for V0 witness program - must be 20 or 32 bytes');
        }

        $this->version = $version;
        $this->program = $program;
    }

    public static function v0(BufferInterface $program): WitnessProgram
    {
        if ($program->getSize() === 20) {
            return new self(self::V0, $program);
        } elseif ($program->getSize() === 32) {
            return new self(self::V0, $program);
        } else {
            throw new \RuntimeException('Invalid size for V0 witness program - must be 20 or 32 bytes');
        }
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getProgram(): BufferInterface
    {
        return $this->program;
    }

    public function getScript(): ScriptInterface
    {
        if ($this->outputScript === null) {
            $this->outputScript = ScriptFactory::sequence([encodeOpN($this->version), $this->program]);
        }

        return $this->outputScript;
    }
}
