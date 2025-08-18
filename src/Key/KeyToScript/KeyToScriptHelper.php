<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Key\KeyToScript;

use BitWasp\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\EcSerializer;
use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use BitWasp\Bitcoin\Key\KeyToScript\Decorator\P2shP2wshScriptDecorator;
use BitWasp\Bitcoin\Key\KeyToScript\Decorator\P2shScriptDecorator;
use BitWasp\Bitcoin\Key\KeyToScript\Decorator\P2wshScriptDecorator;
use BitWasp\Bitcoin\Key\KeyToScript\Factory\KeyToScriptDataFactory;
use BitWasp\Bitcoin\Key\KeyToScript\Factory\MultisigScriptDataFactory;
use BitWasp\Bitcoin\Key\KeyToScript\Factory\P2pkhScriptDataFactory;
use BitWasp\Bitcoin\Key\KeyToScript\Factory\P2wpkhScriptDataFactory;

class KeyToScriptHelper
{
    /**
     * @var PublicKeySerializerInterface
     */
    private $pubKeySer;

    /**
     * Slip132PrefixRegistry constructor.
     */
    public function __construct(EcAdapterInterface $ecAdapter)
    {
        $this->pubKeySer = EcSerializer::getSerializer(PublicKeySerializerInterface::class, true, $ecAdapter);
    }

    public function getP2pkhFactory(): P2pkhScriptDataFactory
    {
        return new P2pkhScriptDataFactory($this->pubKeySer);
    }

    public function getMultisigFactory(int $numSignatures, int $numKeys, bool $sortCosignKeys): MultisigScriptDataFactory
    {
        return new MultisigScriptDataFactory($numSignatures, $numKeys, $sortCosignKeys, $this->pubKeySer);
    }

    public function getP2wpkhFactory(): P2wpkhScriptDataFactory
    {
        return new P2wpkhScriptDataFactory($this->pubKeySer);
    }

    /**
     * @throws \BitWasp\Bitcoin\Exceptions\DisallowedScriptDataFactoryException
     */
    public function getP2shFactory(KeyToScriptDataFactory $scriptFactory): ScriptDataFactory
    {
        return new P2shScriptDecorator($scriptFactory);
    }

    /**
     * @throws \BitWasp\Bitcoin\Exceptions\DisallowedScriptDataFactoryException
     */
    public function getP2wshFactory(KeyToScriptDataFactory $scriptFactory): ScriptDataFactory
    {
        return new P2wshScriptDecorator($scriptFactory);
    }

    /**
     * @throws \BitWasp\Bitcoin\Exceptions\DisallowedScriptDataFactoryException
     */
    public function getP2shP2wshFactory(KeyToScriptDataFactory $scriptFactory): ScriptDataFactory
    {
        return new P2shP2wshScriptDecorator($scriptFactory);
    }
}
