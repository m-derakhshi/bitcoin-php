<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Address;

use BitWasp\Bitcoin\Network\NetworkInterface;
use BitWasp\Bitcoin\Script\ScriptInterface;

abstract class BaseAddressCreator
{
    abstract public function fromString(string $strAddress, ?NetworkInterface $network = null): Address;

    abstract public function fromOutputScript(ScriptInterface $script): Address;
}
