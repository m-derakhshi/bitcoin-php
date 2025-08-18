<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Serializer\Key\HierarchicalKey;

use BitWasp\Bitcoin\Base58;
use BitWasp\Bitcoin\Key\Deterministic\HierarchicalKey;
use BitWasp\Bitcoin\Network\NetworkInterface;

class Base58ExtendedKeySerializer
{
    /**
     * @var ExtendedKeySerializer
     */
    private $serializer;

    public function __construct(ExtendedKeySerializer $hdSerializer)
    {
        $this->serializer = $hdSerializer;
    }

    /**
     * @throws \Exception
     */
    public function serialize(NetworkInterface $network, HierarchicalKey $key): string
    {
        return Base58::encodeCheck($this->serializer->serialize($network, $key));
    }

    /**
     * @throws \BitWasp\Bitcoin\Exceptions\Base58ChecksumFailure
     * @throws \BitWasp\Buffertools\Exceptions\ParserOutOfRange
     */
    public function parse(NetworkInterface $network, string $base58): HierarchicalKey
    {
        return $this->serializer->parse($network, Base58::decodeCheck($base58));
    }
}
