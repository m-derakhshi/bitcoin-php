<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Key;

use BitWasp\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Impl\PhpEcc\Key\PublicKey;
use BitWasp\Bitcoin\Key\Factory\PublicKeyFactory;
use BitWasp\Bitcoin\Tests\AbstractTestCase;
use BitWasp\Buffertools\Buffer;

class PublicKeyTest extends AbstractTestCase
{
    public function getPublicVectors()
    {
        $json = json_decode($this->dataFile('publickey.compressed.json'));
        $results = [];
        foreach ($json->test as $test) {
            foreach ($this->getEcAdapters() as $adapter) {
                $results[] = [
                    $adapter[0],
                    $test->compressed,
                    $test->uncompressed,
                ];
            }
        }

        return $results;
    }

    /**
     * @dataProvider getPublicVectors
     */
    public function test_from_hex(EcAdapterInterface $ecAdapter, string $eCompressed, string $eUncompressed)
    {
        $pubKeyFactory = new PublicKeyFactory($ecAdapter);
        $publicKey = $pubKeyFactory->fromHex($eCompressed);

        $this->assertSame($eCompressed, $publicKey->getBuffer()->getHex());
        $this->assertSame($publicKey->getBuffer()->getHex(), $eCompressed);
        $this->assertTrue($publicKey->isCompressed());
    }

    /**
     * @dataProvider getPublicVectors
     */
    public function test_from_hex_uncompressed(EcAdapterInterface $ecAdapter, string $eCompressed, string $eUncompressed)
    {
        $pubKeyFactory = new PublicKeyFactory($ecAdapter);
        $publicKey = $pubKeyFactory->fromHex($eUncompressed);
        $this->assertSame($eUncompressed, $publicKey->getBuffer()->getHex());
        $this->assertSame($publicKey->getBuffer()->getHex(), $eUncompressed);
        $this->assertFalse($publicKey->isCompressed());
        $this->assertFalse($publicKey->isPrivate());
    }

    /**
     * @dataProvider getEcAdapters
     *
     * @expectedException \Exception
     */
    public function test_from_hex_invalid_length(EcAdapterInterface $ecAdapter)
    {
        $hex = '02cffc9fcdc2a4e6f5dd91aee9d8d79828c1c93e7a76949a451aab8be6a0c44febaa';
        $pubKeyFactory = new PublicKeyFactory($ecAdapter);
        $pubKeyFactory->fromHex($hex);
    }

    /**
     * @expectedException \Exception
     */
    public function test_from_hex_invalid_byte()
    {
        $hex = '01cffc9fcdc2a4e6f5dd91aee9d8d79828c1c93e7a76949a451aab8be6a0c44feb';
        $pubKeyFactory = new PublicKeyFactory;
        $pubKeyFactory->fromHex($hex);
    }

    public function test_is_compressed_or_uncompressed()
    {
        $this->assertFalse(PublicKey::isCompressedOrUncompressed(Buffer::hex('00')));
        $this->assertTrue(PublicKey::isCompressedOrUncompressed(Buffer::hex('0400010203040506070809000102030405060708090001020304050607080900010203040506070809000102030405060708090001020304050607080900010203')));
        $this->assertFalse(PublicKey::isCompressedOrUncompressed(Buffer::hex('0400010203040506070809000102030405060708090001020304050607080900010203040506070809000102030405060708090001020304050607080900')));
        $this->assertFalse(PublicKey::isCompressedOrUncompressed(Buffer::hex('040001020304050607080900010203040506070809000102030405060708090001020304050607080900010203040506070809000102030405060708090001020304')));

        $this->assertTrue(PublicKey::isCompressedOrUncompressed(Buffer::hex('020001020304050607080900010203040506070809000102030405060708090001')));
        $this->assertTrue(PublicKey::isCompressedOrUncompressed(Buffer::hex('030001020304050607080900010203040506070809000102030405060708090001')));
        $this->assertFalse(PublicKey::isCompressedOrUncompressed(Buffer::hex('03000102030405060708090001020304050607080900010203040506070809000102')));
        $this->assertFalse(PublicKey::isCompressedOrUncompressed(Buffer::hex('0300010203040506070809000102030405060708090001020304050607080900')));

        $this->assertFalse(PublicKey::isCompressedOrUncompressed(Buffer::hex('050001020304050607080900010203040506070809000102030405060708090001')));
    }

    /**
     * @expectedException \Exception
     */
    public function test_from_hex_invalid_byte2()
    {
        $hex = '04cffc9fcdc2a4e6f5dd91aee9d8d79828c1c93e7a76949a451aab8be6a0c44feb';
        $pubKeyFactory = new PublicKeyFactory;
        $pubKeyFactory->fromHex($hex);
    }

    public function getPkHashVectors()
    {
        $json = json_decode($this->dataFile('publickey.pubkeyhash.json'));
        $results = [];

        foreach ($json->test as $test) {
            foreach ($this->getEcAdapters() as $ecAdapterFixture) {
                $results[] = [
                    $ecAdapterFixture[0],
                    $test->key,
                    $test->hash,
                ];
            }
        }

        return $results;
    }

    /**
     * @dataProvider getPkHashVectors
     *
     * @param  string  $eKey  - hex public key
     * @param  string  $eHash  - hex sha256ripemd160 of public key
     */
    public function test_pub_key_hash(EcAdapterInterface $ecAdapter, string $eKey, string $eHash)
    {
        $pubKeyFactory = new PublicKeyFactory($ecAdapter);
        $this->assertSame(
            $eHash,
            $pubKeyFactory->fromHex($eKey)
                ->getPubKeyHash()
                ->getHex()
        );
    }

    /**
     * @dataProvider getPublicVectors
     */
    public function test_is_not_compressed(EcAdapterInterface $ecAdapter, string $eCompressed, string $eUncompressed)
    {
        $pubKeyFactory = new PublicKeyFactory($ecAdapter);
        $pub = $pubKeyFactory->fromHex($eCompressed);
        $this->assertTrue($pub->isCompressed());

        $pub = $pubKeyFactory->fromHex($eUncompressed);
        $this->assertFalse($pub->isCompressed());
    }
}
