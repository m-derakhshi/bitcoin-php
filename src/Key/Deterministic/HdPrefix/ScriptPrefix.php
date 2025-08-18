<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Key\Deterministic\HdPrefix;

use BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter;
use BitWasp\Bitcoin\Key\KeyToScript\ScriptDataFactory;

class ScriptPrefix
{
    /**
     * @var string
     */
    private $privatePrefix;

    /**
     * @var string
     */
    private $publicPrefix;

    /**
     * @var ScriptDataFactory
     */
    private $scriptDataFactory;

    /**
     * ScriptPrefixConfig constructor.
     */
    public function __construct(ScriptDataFactory $scriptDataFactory, string $privatePrefix, string $publicPrefix)
    {
        if (strlen($privatePrefix) !== 8) {
            throw new InvalidNetworkParameter('Invalid HD private prefix: wrong length');
        }

        if (! ctype_xdigit($privatePrefix)) {
            throw new InvalidNetworkParameter('Invalid HD private prefix: expecting hex');
        }

        if (strlen($publicPrefix) !== 8) {
            throw new InvalidNetworkParameter('Invalid HD public prefix: wrong length');
        }

        if (! ctype_xdigit($publicPrefix)) {
            throw new InvalidNetworkParameter('Invalid HD public prefix: expecting hex');
        }

        $this->scriptDataFactory = $scriptDataFactory;
        $this->publicPrefix = $publicPrefix;
        $this->privatePrefix = $privatePrefix;
    }

    public function getPrivatePrefix(): string
    {
        return $this->privatePrefix;
    }

    public function getPublicPrefix(): string
    {
        return $this->publicPrefix;
    }

    public function getScriptDataFactory(): ScriptDataFactory
    {
        return $this->scriptDataFactory;
    }
}
