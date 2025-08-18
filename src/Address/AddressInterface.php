<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Address;

use BitWasp\Bitcoin\Network\NetworkInterface;
use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Buffertools\BufferInterface;

interface AddressInterface
{
    public function getAddress(?NetworkInterface $network = null): string;

    public function getHash(): BufferInterface;

    public function getScriptPubKey(): ScriptInterface;
}
