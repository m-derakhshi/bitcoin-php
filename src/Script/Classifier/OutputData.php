<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Script\Classifier;

use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\Script\ScriptType;

class OutputData
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var ScriptInterface
     */
    private $script;

    /**
     * @var mixed
     */
    private $solution;

    /**
     * OutputData constructor.
     *
     * @param  mixed  $solution
     */
    public function __construct(string $type, ScriptInterface $script, $solution)
    {
        $this->type = $type;
        $this->script = $script;
        $this->solution = $solution;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getScript(): ScriptInterface
    {
        return $this->script;
    }

    /**
     * @return mixed
     */
    public function getSolution()
    {
        return $this->solution;
    }

    public function canSign(): bool
    {
        return in_array($this->type, [ScriptType::MULTISIG, ScriptType::P2PK, ScriptType::P2PKH]);
    }
}
