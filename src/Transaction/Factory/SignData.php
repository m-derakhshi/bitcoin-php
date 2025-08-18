<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction\Factory;

use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\Script\WitnessScript;

class SignData
{
    /**
     * @var ScriptInterface
     */
    protected $redeemScript = null;

    /**
     * @var ScriptInterface
     */
    protected $witnessScript = null;

    /**
     * @var int
     */
    protected $signaturePolicy = null;

    /**
     * @var bool[]
     */
    protected $logicalPath = null;

    /**
     * @return $this
     */
    public function p2sh(ScriptInterface $redeemScript)
    {
        if ($redeemScript instanceof WitnessScript) {
            throw new \InvalidArgumentException('Cannot pass WitnessScript as a redeemScript');
        }
        $this->redeemScript = $redeemScript;

        return $this;
    }

    public function hasRedeemScript(): bool
    {
        return $this->redeemScript instanceof ScriptInterface;
    }

    public function getRedeemScript(): ScriptInterface
    {
        if ($this->redeemScript === null) {
            throw new \RuntimeException('Redeem script requested but not set');
        }

        return $this->redeemScript;
    }

    /**
     * @return $this
     */
    public function p2wsh(ScriptInterface $witnessScript)
    {
        $this->witnessScript = $witnessScript;

        return $this;
    }

    public function hasWitnessScript(): bool
    {
        return $this->witnessScript instanceof ScriptInterface;
    }

    public function getWitnessScript(): ScriptInterface
    {
        if ($this->witnessScript === null) {
            throw new \RuntimeException('Witness script requested but not set');
        }

        return $this->witnessScript;
    }

    /**
     * @return $this
     */
    public function signaturePolicy(int $flags)
    {
        $this->signaturePolicy = $flags;

        return $this;
    }

    public function hasSignaturePolicy(): bool
    {
        return $this->signaturePolicy !== null;
    }

    public function getSignaturePolicy(): int
    {
        if ($this->signaturePolicy === null) {
            throw new \RuntimeException('Signature policy requested but not set');
        }

        return $this->signaturePolicy;
    }

    /**
     * @param  bool[]  $vfPathTaken
     * @return $this
     */
    public function logicalPath(array $vfPathTaken)
    {
        foreach ($vfPathTaken as $value) {
            if (! is_bool($value)) {
                throw new \RuntimeException('Invalid values for logical path, must be a boolean array');
            }
        }

        $this->logicalPath = $vfPathTaken;

        return $this;
    }

    public function hasLogicalPath(): bool
    {
        return is_array($this->logicalPath);
    }

    /**
     * @return bool[]
     */
    public function getLogicalPath(): array
    {
        if ($this->logicalPath === null) {
            throw new \RuntimeException('Logical path requested but not set');
        }

        return $this->logicalPath;
    }
}
