<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Key\KeyToScript;

use BitWasp\Bitcoin\Crypto\EcAdapter\Key\KeyInterface;

abstract class ScriptDataFactory
{
    abstract public function convertKey(KeyInterface ...$keys): ScriptAndSignData;

    abstract public function getScriptType(): string;
}
