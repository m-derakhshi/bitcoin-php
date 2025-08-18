<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Signature;

use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Adapter\EcAdapter;
use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Serializer\Signature\DerSignatureSerializer;
use BitWasp\Bitcoin\Crypto\EcAdapter\Signature\SignatureInterface;
use BitWasp\Bitcoin\Serializable;
use BitWasp\Buffertools\BufferInterface;

class Signature extends Serializable implements \Mdanter\Ecc\Crypto\Signature\SignatureInterface, SignatureInterface
{
    /**
     * @var \GMP
     */
    private $r;

    /**
     * @var \GMP
     */
    private $s;

    /**
     * @var EcAdapter
     */
    private $ecAdapter;

    public function __construct(EcAdapter $ecAdapter, \GMP $r, \GMP $s)
    {
        $this->ecAdapter = $ecAdapter;
        $this->r = $r;
        $this->s = $s;
    }

    /**
     * {@inheritdoc}
     *
     * @see SignatureInterface::getR()
     */
    public function getR(): \GMP
    {
        return $this->r;
    }

    /**
     * {@inheritdoc}
     *
     * @see SignatureInterface::getS()
     */
    public function getS(): \GMP
    {
        return $this->s;
    }

    public function doEquals(Signature $signature): bool
    {
        $math = $this->ecAdapter->getMath();

        return $math->equals($this->getR(), $signature->getR())
            && $math->equals($this->getS(), $signature->getS());
    }

    public function equals(SignatureInterface $signature): bool
    {
        /** @var Signature $signature */
        return $this->doEquals($signature);
    }

    public function getBuffer(): BufferInterface
    {
        return (new DerSignatureSerializer($this->ecAdapter))->serialize($this);
    }

    public function getSignatureType(): string
    {
        return 'ecdsa';
    }
}
