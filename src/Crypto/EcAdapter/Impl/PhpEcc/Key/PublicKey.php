<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Key;

use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Adapter\EcAdapter;
use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Serializer\Key\PublicKeySerializer;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\Key;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\KeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Signature\SignatureInterface;
use BitWasp\Buffertools\BufferInterface;
use Mdanter\Ecc\Crypto\Signature\Signer;
use Mdanter\Ecc\Primitives\CurveFpInterface;
use Mdanter\Ecc\Primitives\GeneratorPoint;
use Mdanter\Ecc\Primitives\PointInterface;

class PublicKey extends Key implements \Mdanter\Ecc\Crypto\Key\PublicKeyInterface, PublicKeyInterface
{
    /**
     * @var EcAdapter
     */
    private $ecAdapter;

    /**
     * @var PointInterface
     */
    private $point;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var bool
     */
    private $compressed;

    /**
     * PublicKey constructor.
     */
    public function __construct(
        EcAdapter $ecAdapter,
        PointInterface $point,
        bool $compressed = false,
        ?string $prefix = null
    ) {
        $this->ecAdapter = $ecAdapter;
        $this->point = $point;
        $this->prefix = $prefix;
        $this->compressed = $compressed;
    }

    public function getGenerator(): GeneratorPoint
    {
        return $this->ecAdapter->getGenerator();
    }

    public function getCurve(): CurveFpInterface
    {
        return $this->ecAdapter->getGenerator()->getCurve();
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    public function getPoint(): PointInterface
    {
        return $this->point;
    }

    public function verify(BufferInterface $msg32, SignatureInterface $signature): bool
    {
        $hash = gmp_init($msg32->getHex(), 16);
        $signer = new Signer($this->ecAdapter->getMath());

        return $signer->verify($this, $signature, $hash);
    }

    public function tweakAdd(\GMP $tweak): KeyInterface
    {
        $offset = $this->ecAdapter->getGenerator()->mul($tweak);
        $newPoint = $this->point->add($offset);

        return new PublicKey($this->ecAdapter, $newPoint, $this->compressed);
    }

    public function tweakMul(\GMP $tweak): KeyInterface
    {
        $point = $this->point->mul($tweak);

        return new PublicKey($this->ecAdapter, $point, $this->compressed);
    }

    public static function isCompressedOrUncompressed(BufferInterface $publicKey): bool
    {
        $vchPubKey = $publicKey->getBinary();
        if ($publicKey->getSize() < self::LENGTH_COMPRESSED) {
            return false;
        }

        if ($vchPubKey[0] === self::KEY_UNCOMPRESSED) {
            if ($publicKey->getSize() !== self::LENGTH_UNCOMPRESSED) {
                // Invalid length for uncompressed key
                return false;
            }
        } elseif (in_array($vchPubKey[0], [
            self::KEY_COMPRESSED_EVEN,
            self::KEY_COMPRESSED_ODD,
        ])) {
            if ($publicKey->getSize() !== self::LENGTH_COMPRESSED) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    public function isCompressed(): bool
    {
        return $this->compressed;
    }

    private function doEquals(PublicKey $other): bool
    {
        return $this->compressed === $other->compressed
            && $this->point->equals($other->point)
            && (($this->prefix === null || $other->prefix === null) || ($this->prefix === $other->prefix));
    }

    public function equals(PublicKeyInterface $other): bool
    {
        /** @var self $other */
        return $this->doEquals($other);
    }

    public function getBuffer(): BufferInterface
    {
        return (new PublicKeySerializer($this->ecAdapter))->serialize($this);
    }
}
