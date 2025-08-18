<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction\Mutator;

use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\Transaction\TransactionOutput;
use BitWasp\Bitcoin\Transaction\TransactionOutputInterface;

class OutputMutator
{
    /**
     * @var TransactionOutputInterface
     */
    private $output;

    public function __construct(TransactionOutputInterface $output)
    {
        $this->output = $output;
    }

    public function done(): TransactionOutputInterface
    {
        return $this->output;
    }

    /**
     * @return $this
     */
    private function replace(array $array)
    {
        $this->output = new TransactionOutput(
            array_key_exists('value', $array) ? $array['value'] : $this->output->getValue(),
            array_key_exists('script', $array) ? $array['script'] : $this->output->getScript()
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function value(int $value)
    {
        return $this->replace(['value' => $value]);
    }

    /**
     * @return $this
     */
    public function script(ScriptInterface $script)
    {
        return $this->replace(['script' => $script]);
    }
}
