<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction\Mutator;

use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\Transaction\OutPoint;
use BitWasp\Bitcoin\Transaction\OutPointInterface;
use BitWasp\Bitcoin\Transaction\TransactionInput;
use BitWasp\Bitcoin\Transaction\TransactionInputInterface;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class InputMutator
{
    /**
     * @var TransactionInputInterface
     */
    private $input;

    public function __construct(TransactionInputInterface $input)
    {
        $this->input = $input;
    }

    public function done(): TransactionInputInterface
    {
        return $this->input;
    }

    /**
     * @return $this
     */
    private function replace(array $array = [])
    {
        $this->input = new TransactionInput(
            array_key_exists('outpoint', $array) ? $array['outpoint'] : $this->input->getOutPoint(),
            array_key_exists('script', $array) ? $array['script'] : $this->input->getScript(),
            array_key_exists('nSequence', $array) ? $array['nSequence'] : $this->input->getSequence()
        );

        return $this;
    }

    /**
     * @return InputMutator
     */
    public function outpoint(OutPointInterface $outPoint)
    {
        return $this->replace(['outpoint' => $outPoint]);
    }

    /**
     * @return $this
     */
    public function null()
    {
        return $this->replace(['outpoint' => new OutPoint(new Buffer(str_pad('', 32, "\x00"), 32), 0xFFFFFFFF)]);
    }

    /**
     * @return $this
     */
    public function txid(BufferInterface $txid)
    {
        return $this->replace(['txid' => $txid]);
    }

    /**
     * @return InputMutator
     */
    public function vout(int $vout)
    {
        return $this->replace(['vout' => $vout]);
    }

    /**
     * @return $this
     */
    public function script(ScriptInterface $script)
    {
        return $this->replace(['script' => $script]);
    }

    /**
     * @return $this
     */
    public function sequence(int $nSequence)
    {
        return $this->replace(['nSequence' => $nSequence]);
    }
}
