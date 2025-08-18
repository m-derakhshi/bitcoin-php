<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Crypto\EcAdapter\Impl\Secp256k1\Signature;

use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\Secp256k1\Adapter\EcAdapter;
use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\Secp256k1\Serializer\Signature\CompactSignatureSerializer;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class CompactSignature extends Signature implements CompactSignatureInterface
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * @var bool
     */
    private $compressed;

    /**
     * @var int
     */
    private $recid;

    /**
     * @var EcAdapter
     */
    private $ecAdapter;

    /**
     * @param  resource  $secp256k1_ecdsa_signature_t
     */
    public function __construct(EcAdapter $ecAdapter, $secp256k1_ecdsa_signature_t, int $recid, bool $compressed)
    {
        if (! is_resource($secp256k1_ecdsa_signature_t)
            || get_resource_type($secp256k1_ecdsa_signature_t) !== SECP256K1_TYPE_RECOVERABLE_SIG
        ) {
            throw new \RuntimeException('CompactSignature: must pass recoverable signature resource');
        }

        $ser = '';
        $recidout = 0;
        secp256k1_ecdsa_recoverable_signature_serialize_compact($ecAdapter->getContext(), $ser, $recidout, $secp256k1_ecdsa_signature_t);
        [$r, $s] = array_map(
            function ($val) {
                return (new Buffer($val))->getGmp();
            },
            str_split($ser, 32)
        );

        $this->resource = $secp256k1_ecdsa_signature_t;
        $this->recid = $recid;
        $this->compressed = $compressed;
        $this->ecAdapter = $ecAdapter;
        parent::__construct($ecAdapter, $r, $s, $secp256k1_ecdsa_signature_t);
    }

    public function convert(): Signature
    {
        $sig_t = '';
        /** @var resource $sig_t */
        secp256k1_ecdsa_recoverable_signature_convert($this->ecAdapter->getContext(), $sig_t, $this->resource);

        return new Signature($this->ecAdapter, $this->getR(), $this->getS(), $sig_t);
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function getRecoveryId(): int
    {
        return $this->recid;
    }

    public function getFlags(): int
    {
        return $this->getRecoveryId() + 27 + ($this->isCompressed() ? 4 : 0);
    }

    public function isCompressed(): bool
    {
        return $this->compressed;
    }

    public function getBuffer(): BufferInterface
    {
        return (new CompactSignatureSerializer($this->ecAdapter))->serialize($this);
    }
}
