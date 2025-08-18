<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Serializer\Signature;

use BitWasp\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\EcAdapterFactory;
use BitWasp\Bitcoin\Crypto\EcAdapter\EcSerializer;
use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Signature\Signature as PhpeccSignature;
use BitWasp\Bitcoin\Crypto\EcAdapter\Serializer\Signature\DerSignatureSerializerInterface;
use BitWasp\Bitcoin\Math\Math;
use BitWasp\Bitcoin\Tests\AbstractTestCase;
use BitWasp\Buffertools\Buffer;
use Mdanter\Ecc\EccFactory;

class DerSignatureSerializerTest extends AbstractTestCase
{
    /**
     * @dataProvider getEcAdapters
     *
     * @expectedException \Exception
     */
    public function test_from_parser_failure(EcAdapterInterface $adapter)
    {
        /** @var DerSignatureSerializerInterface $serializer */
        $serializer = EcSerializer::getSerializer(DerSignatureSerializerInterface::class, true, $adapter);
        $serializer->parse(new Buffer);
    }

    public function test_phpecc_is_consistent()
    {
        $r = 1;
        $s = 1;
        $adapter = EcAdapterFactory::getPhpEcc(new Math, EccFactory::getSecgCurves()->generator256k1());
        $signature = new PhpeccSignature($adapter, gmp_init($r), gmp_init($s));
        /** @var DerSignatureSerializerInterface $serializer */
        $serializer = EcSerializer::getSerializer(DerSignatureSerializerInterface::class, true, $adapter);
        $buffer = $serializer->serialize($signature);
        $parsed = $serializer->parse($buffer);
        $this->assertEquals($signature->getR(), $parsed->getR());
        $this->assertEquals($signature->getS(), $parsed->getS());
    }
}
