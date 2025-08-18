<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Address;

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Network\NetworkInterface;
use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\Script\WitnessProgram;

class SegwitAddress extends Address implements Bech32AddressInterface
{
    /**
     * @var WitnessProgram
     */
    protected $witnessProgram;

    /**
     * SegwitAddress constructor.
     */
    public function __construct(WitnessProgram $witnessProgram)
    {
        $this->witnessProgram = $witnessProgram;

        parent::__construct($witnessProgram->getProgram());
    }

    public function getHRP(?NetworkInterface $network = null): string
    {
        $network = $network ?: Bitcoin::getNetwork();

        return $network->getSegwitBech32Prefix();
    }

    public function getWitnessProgram(): WitnessProgram
    {
        return $this->witnessProgram;
    }

    public function getScriptPubKey(): ScriptInterface
    {
        return $this->witnessProgram->getScript();
    }

    public function getAddress(?NetworkInterface $network = null): string
    {
        $network = $network ?: Bitcoin::getNetwork();

        return \BitWasp\Bech32\encodeSegwit($network->getSegwitBech32Prefix(), $this->witnessProgram->getVersion(), $this->witnessProgram->getProgram()->getBinary());
    }
}
