<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Key\KeyToScript\Factory;

use BitWasp\Bitcoin\Crypto\EcAdapter\EcSerializer;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\KeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PrivateKeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use BitWasp\Bitcoin\Key\KeyToScript\ScriptAndSignData;
use BitWasp\Bitcoin\Key\KeyToScript\ScriptDataFactory;

abstract class KeyToScriptDataFactory extends ScriptDataFactory
{
    /**
     * @var PublicKeySerializerInterface
     */
    protected $pubKeySerializer;

    /**
     * KeyToP2PKScriptFactory constructor.
     */
    public function __construct(?PublicKeySerializerInterface $pubKeySerializer = null)
    {
        if ($pubKeySerializer === null) {
            $pubKeySerializer = EcSerializer::getSerializer(PublicKeySerializerInterface::class, true);
        }

        $this->pubKeySerializer = $pubKeySerializer;
    }

    abstract protected function convertKeyToScriptData(PublicKeyInterface ...$keys): ScriptAndSignData;

    public function convertKey(KeyInterface ...$keys): ScriptAndSignData
    {
        $pubs = [];
        foreach ($keys as $key) {
            if ($key instanceof PrivateKeyInterface) {
                $key = $key->getPublicKey();
            }
            $pubs[] = $key;
        }

        return $this->convertKeyToScriptData(...$pubs);
    }
}
