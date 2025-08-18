<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Adapter;

use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PrivateKeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Signature\CompactSignatureInterface;
use BitWasp\Bitcoin\Math\Math;
use BitWasp\Buffertools\BufferInterface;

interface EcAdapterInterface
{
    public function getMath(): Math;

    /**
     * @return \Mdanter\Ecc\Primitives\GeneratorPoint
     */
    public function getGenerator();

    public function getOrder(): \GMP;

    public function validatePrivateKey(BufferInterface $buffer): bool;

    /**
     * @param  bool|false  $halfOrder
     */
    public function validateSignatureElement(\GMP $element, bool $halfOrder = false): bool;

    /**
     * @param  bool|false  $compressed
     */
    public function getPrivateKey(\GMP $scalar, bool $compressed = false): PrivateKeyInterface;

    public function recover(BufferInterface $messageHash, CompactSignatureInterface $compactSignature): PublicKeyInterface;
}
