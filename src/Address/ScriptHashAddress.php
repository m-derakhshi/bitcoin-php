<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Address;

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Network\NetworkInterface;
use BitWasp\Bitcoin\Script\ScriptFactory;
use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Buffertools\BufferInterface;

class ScriptHashAddress extends Base58Address
{
    /**
     * ScriptHashAddress constructor.
     */
    public function __construct(BufferInterface $data)
    {
        if ($data->getSize() !== 20) {
            throw new \RuntimeException('P2SH address hash should be 20 bytes');
        }

        parent::__construct($data);
    }

    public function getPrefixByte(?NetworkInterface $network = null): string
    {
        $network = $network ?: Bitcoin::getNetwork();

        return pack('H*', $network->getP2shByte());
    }

    public function getScriptPubKey(): ScriptInterface
    {
        return ScriptFactory::scriptPubKey()->payToScriptHash($this->getHash());
    }
}
