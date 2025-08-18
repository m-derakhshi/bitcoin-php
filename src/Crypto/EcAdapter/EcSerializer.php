<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter;

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Key\PrivateKeySerializerInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Key\PublicKeySerializerInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Signature\CompactSignatureSerializerInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Signature\DerSignatureSerializerInterface;

class EcSerializer
{
    const PATH_PHPECC = 'BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\\';

    const PATH_SECP256K1 = 'BitWasp\Bitcoin\Crypto\EcAdapter\Impl\Secp256k1\\';

    /**
     * @var string[]
     */
    private static $serializerInterface = [
        PrivateKeySerializerInterface::class,
        PublicKeySerializerInterface::class,
        CompactSignatureSerializerInterface::class,
        DerSignatureSerializerInterface::class,
    ];

    /**
     * @var string[]
     */
    private static $serializerImpl = [
        'Serializer\Key\PrivateKeySerializer',
        'Serializer\Key\PublicKeySerializer',
        'Serializer\Signature\CompactSignatureSerializer',
        'Serializer\Signature\DerSignatureSerializer',
    ];

    /**
     * @var array
     */
    private static $map = [];

    /**
     * @var bool
     */
    private static $useCache = true;

    /**
     * @var array
     */
    private static $cache = [];

    public static function getImplRelPath(string $interface): string
    {
        if (count(self::$map) === 0) {
            if (! in_array($interface, self::$serializerInterface, true)) {
                throw new \InvalidArgumentException('Interface not known');
            }

            $cInterface = count(self::$serializerInterface);
            if ($cInterface !== count(self::$serializerImpl)) {
                throw new \InvalidArgumentException('Invalid serializer interface map');
            }

            for ($i = 0; $i < $cInterface; $i++) {
                /** @var string $iface */
                $iface = self::$serializerInterface[$i];
                $ipath = self::$serializerImpl[$i];
                self::$map[$iface] = $ipath;
            }
        }

        return self::$map[$interface];
    }

    public static function getImplPaths(): array
    {
        return [
            'BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Adapter\EcAdapter' => 'BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\\',
            'BitWasp\Bitcoin\Crypto\EcAdapter\Impl\Secp256k1\Adapter\EcAdapter' => 'BitWasp\Bitcoin\Crypto\EcAdapter\Impl\Secp256k1\\',
        ];
    }

    public static function getAdapterImplPath(EcAdapterInterface $adapter): string
    {
        $paths = static::getImplPaths();
        $class = get_class($adapter);
        if (! isset($paths[$class])) {
            throw new \RuntimeException('Unknown EcAdapter');
        }

        return $paths[$class];
    }

    /**
     * @param  bool  $useCache
     * @return mixed
     */
    public static function getSerializer(string $interface, $useCache = true, ?EcAdapterInterface $adapter = null)
    {
        if ($adapter === null) {
            $adapter = Bitcoin::getEcAdapter();
        }

        $key = get_class($adapter).':'.$interface;
        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }

        $classPath = self::getAdapterImplPath($adapter).self::getImplRelPath($interface);
        $class = new $classPath($adapter);

        if ($useCache && self::$useCache) {
            self::$cache[$key] = $class;
        }

        return $class;
    }

    /**
     * Disables caching of serializers
     */
    public static function disableCache()
    {
        self::$useCache = false;
        self::$cache = [];
    }
}
