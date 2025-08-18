<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Key\KeyToScript;

use BitWasp\Bitcoin\Address\Address;
use BitWasp\Bitcoin\Address\BaseAddressCreator;
use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\Transaction\Factory\SignData;

class ScriptAndSignData
{
    /**
     * @var ScriptInterface
     */
    private $scriptPubKey;

    /**
     * @var SignData
     */
    private $signData;

    /**
     * ScriptAndSignData constructor.
     */
    public function __construct(ScriptInterface $scriptPubKey, SignData $signData)
    {
        $this->scriptPubKey = $scriptPubKey;
        $this->signData = $signData;
    }

    public function getScriptPubKey(): ScriptInterface
    {
        return $this->scriptPubKey;
    }

    public function getAddress(BaseAddressCreator $creator): Address
    {
        return $creator->fromOutputScript($this->scriptPubKey);
    }

    public function getSignData(): SignData
    {
        return $this->signData;
    }
}
