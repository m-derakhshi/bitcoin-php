<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Bloom;

use BitWasp\Bitcoin\Amount;
use BitWasp\Bitcoin\Bloom\BloomFilter;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use BitWasp\Bitcoin\Key\Factory\PublicKeyFactory;
use BitWasp\Bitcoin\Math\Math;
use BitWasp\Bitcoin\Script\ScriptFactory;
use BitWasp\Bitcoin\Serializer\Bloom\BloomFilterSerializer;
use BitWasp\Bitcoin\Tests\AbstractTestCase;
use BitWasp\Bitcoin\Transaction\OutPoint;
use BitWasp\Bitcoin\Transaction\Transaction;
use BitWasp\Bitcoin\Transaction\TransactionFactory;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;

class BloomFilterTest extends AbstractTestCase
{
    /**
     * @var PublicKeyFactory
     */
    private $pubKeyFactory;

    protected function setUp()
    {
        $this->pubKeyFactory = new PublicKeyFactory;
        parent::setUp();
    }

    /**
     * @return BloomFilter
     */
    private function parseFilter(BufferInterface $hex)
    {
        return (new BloomFilterSerializer)->parse($hex);
    }

    /**
     * @return BloomFilter
     *
     * @throws \Exception
     */
    private function getEmptyFilterVector()
    {
        return $this->parseFilter(Buffer::hex('2200000000000000000000000000000000000000000000000000000000000000000000120000000000000001'));
    }

    /**
     * @return BloomFilter
     *
     * @throws \Exception
     */
    private function getFullFilterVector()
    {
        return $this->parseFilter(Buffer::hex('22FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF120000000000000001'));
    }

    /**
     * @return \BitWasp\Bitcoin\Transaction\TransactionInterface
     */
    private function getPayToPubkeyTxVector(PublicKeyInterface $publicKey)
    {
        return TransactionFactory::build()
            ->input('0000000000000000000000000000000000000000000000000000000000000000', 0)
            ->output(50 * Amount::COIN, ScriptFactory::scriptPubKey()->payToPubKey($publicKey))
            ->get();
    }

    /**
     * @return \BitWasp\Bitcoin\Transaction\TransactionInterface
     */
    private function getPayToMultisigTxVector(PublicKeyInterface $publicKey)
    {
        return TransactionFactory::build()
            ->input('0000000000000000000000000000000000000000000000000000000000000000', 0)
            ->output(50 * Amount::COIN, ScriptFactory::scriptPubKey()->multisig(1, [$publicKey]))
            ->get();
    }

    public function test_basics()
    {
        $math = new Math;
        $flags = BloomFilter::UPDATE_ALL;
        $filter = BloomFilter::create($math, 3, 0.01, 0, $flags);

        $buff = [
            Buffer::hex('99108ad8ed9bb6274d3980bab5a85c048f0950c8'),
            Buffer::hex('b5a2c786d9ef4658287ced5914b37a1b4aa32eee'),
            Buffer::hex('b9300670b4c5366e95b2699e8b18bc75e5f729c5'),
        ];

        $bytes = Buffer::hex('a9030f7dbeb53a6ec0c2a1908b18b4c5eb67c2c1');

        foreach ($buff as $buf) {
            $filter->insertData($buf);
            $this->assertTrue($filter->containsData($buf));
            $this->assertFalse($filter->containsData($bytes));
        }

        $this->assertEquals('03614e9b050000000000000001', $filter->getBuffer()->getHex());
    }

    public function test_empty_contains()
    {
        $math = new Math;
        $flags = BloomFilter::UPDATE_ALL;
        $filter = BloomFilter::create($math, 3, 0.01, 0, $flags);
        $this->assertFalse($filter->containsData(new Buffer));
    }

    public function test_empty_acceptable_size()
    {
        $math = new Math;
        $flags = BloomFilter::UPDATE_ALL;
        $filter = BloomFilter::create($math, 3, 0.01, 0, $flags);
        $this->assertTrue($filter->hasAcceptableSize());
    }

    public function test_empty_relevant_and_update_tx()
    {
        $math = new Math;
        $flags = BloomFilter::UPDATE_ALL;
        $filter = BloomFilter::create($math, 3, 0.01, 0, $flags);
        $this->assertFalse($filter->isRelevantAndUpdate(new Transaction));
    }

    public function test_basics2()
    {
        $math = new Math;
        $flags = BloomFilter::UPDATE_ALL;
        $filter = BloomFilter::create($math, 3, 0.01, 2147483649, $flags);

        $buff = [
            Buffer::hex('99108ad8ed9bb6274d3980bab5a85c048f0950c8'),
            Buffer::hex('b5a2c786d9ef4658287ced5914b37a1b4aa32eee'),
            Buffer::hex('b9300670b4c5366e95b2699e8b18bc75e5f729c5'),
        ];

        $bytes = Buffer::hex('4141414141414141414141414141414141414141414141414141414141414141');

        foreach ($buff as $buf) {
            $filter->insertData($buf);
            $this->assertTrue($filter->containsData($buf));
            $this->assertFalse($filter->containsData($bytes));
        }

        $this->assertEquals('03ce4299050000000100008001', $filter->getBuffer()->getHex());
        $parser = new BloomFilterSerializer;
        $parse = $parser->parse($filter->getBuffer());
        $this->assertEquals($filter, $parse);
    }

    public function test_flag_checks()
    {
        $math = new Math;
        $flagsAll = BloomFilter::UPDATE_ALL;
        $filter = BloomFilter::create($math, 3, 0.01, 2147483649, $flagsAll);
        $this->assertTrue($filter->isUpdateAll());
        $this->assertFalse($filter->isUpdateNone());
        $this->assertFalse($filter->isUpdatePubKeyOnly());

        $flagsNone = BloomFilter::UPDATE_NONE;
        $filter = BloomFilter::create($math, 3, 0.01, 2147483649, $flagsNone);
        $this->assertTrue($filter->isUpdateNone());
        $this->assertFalse($filter->isUpdatePubKeyOnly());
        $this->assertFalse($filter->isUpdateAll());

        $flagsP2P = BloomFilter::UPDATE_P2PUBKEY_ONLY;
        $filter = BloomFilter::create($math, 3, 0.01, 2147483649, $flagsP2P);
        $this->assertTrue($filter->isUpdatePubKeyOnly());
        $this->assertFalse($filter->isUpdateNone());
        $this->assertFalse($filter->isUpdateAll());
    }

    public function test_for_a_false_positive()
    {
        /*
         * This test serves to ensure the behaviour of bloom filters.
         * 3 known values are inserted into the filter.
         * 2 values are checked against the filter - however these are obviously false positives.
         * 2 values are checked against the filter, which returns a definite no.
         */

        $math = new Math;
        $flags = BloomFilter::UPDATE_ALL;
        $filter = BloomFilter::create($math, 3, 0.01, 2147483649, $flags);

        foreach ([
            Buffer::hex('99108ad8ed9bb6274d3980bab5a85c048f0950c8'),
            Buffer::hex('b5a2c786d9ef4658287ced5914b37a1b4aa32eee'),
            Buffer::hex('b9300670b4c5366e95b2699e8b18bc75e5f729c5'),
        ] as $buf) {
            $filter->insertData($buf);
            $this->assertTrue($filter->containsData($buf));
        }

        $falsePositives = [
            Buffer::hex('a408413bbc084c4875f73149052cc343aa00d0c913fe54d7f6d3821d432fceef'),
            Buffer::hex('f7ef30d3f2371e402a1533892155112fb14f783ac7d622e5f4648ad5b61161cf'),
        ];

        foreach ($falsePositives as $buf) {
            $this->assertTrue($filter->containsData($buf));
        }

        $returnsNotFound = [
            Buffer::hex('4a1'),
            Buffer::hex('4190'),
        ];

        foreach ($returnsNotFound as $buf) {
            $this->assertFalse($filter->containsData($buf));
        }
    }

    public function test_insert_key()
    {
        $pub = $this->pubKeyFactory->fromHex('045b81f0017e2091e2edcd5eecf10d5bdd120a5514cb3ee65b8447ec18bfc4575c6d5bf415e54e03b1067934a0f0ba76b01c6b9ab227142ee1d543764b69d901e0');
        $math = new Math;
        $flags = BloomFilter::UPDATE_ALL;
        $filter = BloomFilter::create($math, 2, 0.001, 0, $flags);

        $filter->insertData($pub->getBuffer());
        $hash = $pub->getPubKeyHash();
        $filter->insertData($hash);

        $this->assertEquals('038fc16b080000000000000001', $filter->getBuffer()->getHex());
    }

    public function test_empty_filter_never_matches()
    {
        $pubkey = $this->pubKeyFactory->fromHex('045b81f0017e2091e2edcd5eecf10d5bdd120a5514cb3ee65b8447ec18bfc4575c6d5bf415e54e03b1067934a0f0ba76b01c6b9ab227142ee1d543764b69d901e0');
        $spends = $this->getPayToPubkeyTxVector($pubkey);

        $filter = $this->getEmptyFilterVector();
        $this->assertFalse($filter->isRelevantAndUpdate($spends));
    }

    public function test_full_filter_always_relevant()
    {
        $pubkey = $this->pubKeyFactory->fromHex('045b81f0017e2091e2edcd5eecf10d5bdd120a5514cb3ee65b8447ec18bfc4575c6d5bf415e54e03b1067934a0f0ba76b01c6b9ab227142ee1d543764b69d901e0');
        $tx = $this->getPayToPubkeyTxVector($pubkey);
        $filter = $this->getFullFilterVector();
        $this->assertTrue($filter->isRelevantAndUpdate($tx));
    }

    public function test_full_filter_always_contains_data()
    {
        $filter = $this->getFullFilterVector();
        $this->assertTrue($filter->containsData(new Buffer('totally unrelated')));
    }

    public function test_full_filter_never_changes()
    {
        $filter = $this->getFullFilterVector();
        $serialized = $filter->getBinary();

        $filter->insertData(new Buffer('new data'));

        $serialized2 = $filter->getBinary();
        $this->assertEquals($serialized, $serialized2);
    }

    public function test_tx_matches_pay_to_pubkey()
    {
        $math = $this->safeMath();
        $pubkey = $this->pubKeyFactory->fromHex('045b81f0017e2091e2edcd5eecf10d5bdd120a5514cb3ee65b8447ec18bfc4575c6d5bf415e54e03b1067934a0f0ba76b01c6b9ab227142ee1d543764b69d901e0');

        $tx = $this->getPayToPubkeyTxVector($pubkey);

        $filter = BloomFilter::create($math, 10, 0.000001, 0, BloomFilter::UPDATE_P2PUBKEY_ONLY);
        $filter->insertData($pubkey->getBuffer());
        $this->assertTrue($filter->isRelevantAndUpdate($tx));
    }

    public function test_tx_matches_pay_to_multisig()
    {
        $math = $this->safeMath();
        $pubkey = $this->pubKeyFactory->fromHex('045b81f0017e2091e2edcd5eecf10d5bdd120a5514cb3ee65b8447ec18bfc4575c6d5bf415e54e03b1067934a0f0ba76b01c6b9ab227142ee1d543764b69d901e0');

        $tx = $this->getPayToMultisigTxVector($pubkey);

        $filter = BloomFilter::create($math, 10, 0.000001, 0, BloomFilter::UPDATE_P2PUBKEY_ONLY);
        $filter->insertData($pubkey->getBuffer());
        $this->assertTrue($filter->isRelevantAndUpdate($tx));
    }

    public function test_tx_matches()
    {
        $math = new Math;
        $hex = '01000000010b26e9b7735eb6aabdf358bab62f9816a21ba9ebdb719d5299e88607d722c190000000008b4830450220070aca44506c5cef3a16ed519d7c3c39f8aab192c4e1c90d065f37b8a4af6141022100a8e160b856c2d43d27d8fba71e5aef6405b8643ac4cb7cb3c462aced7f14711a0141046d11fee51b0e60666d5049a9101a72741df480b96ee26488a4d3466b95c9a40ac5eeef87e10a5cd336c19a84565f80fa6c547957b7700ff4dfbdefe76036c339ffffffff021bff3d11000000001976a91404943fdd508053c75000106d3bc6e2754dbcff1988ac2f15de00000000001976a914a266436d2965547608b9e15d9032a7b9d64fa43188ac00000000';
        $tx = TransactionFactory::fromHex($hex);
        $spends = implode(
            '',
            array_map(
                function ($val) {
                    return str_pad(dechex($val), 2, '0', STR_PAD_LEFT);
                },
                [0x01, 0x00, 0x00, 0x00, 0x01, 0x6B, 0xFF, 0x7F, 0xCD, 0x4F, 0x85, 0x65, 0xEF, 0x40, 0x6D, 0xD5, 0xD6, 0x3D, 0x4F, 0xF9, 0x4F, 0x31, 0x8F, 0xE8, 0x20, 0x27, 0xFD, 0x4D, 0xC4, 0x51, 0xB0, 0x44, 0x74, 0x01, 0x9F, 0x74, 0xB4, 0x00, 0x00, 0x00, 0x00, 0x8C, 0x49, 0x30, 0x46, 0x02, 0x21, 0x00, 0xDA, 0x0D, 0xC6, 0xAE, 0xCE, 0xFE, 0x1E, 0x06, 0xEF, 0xDF, 0x05, 0x77, 0x37, 0x57, 0xDE, 0xB1, 0x68, 0x82, 0x09, 0x30, 0xE3, 0xB0, 0xD0, 0x3F, 0x46, 0xF5, 0xFC, 0xF1, 0x50, 0xBF, 0x99, 0x0C, 0x02, 0x21, 0x00, 0xD2, 0x5B, 0x5C, 0x87, 0x04, 0x00, 0x76, 0xE4, 0xF2, 0x53, 0xF8, 0x26, 0x2E, 0x76, 0x3E, 0x2D, 0xD5, 0x1E, 0x7F, 0xF0, 0xBE, 0x15, 0x77, 0x27, 0xC4, 0xBC, 0x42, 0x80, 0x7F, 0x17, 0xBD, 0x39, 0x01, 0x41, 0x04, 0xE6, 0xC2, 0x6E, 0xF6, 0x7D, 0xC6, 0x10, 0xD2, 0xCD, 0x19, 0x24, 0x84, 0x78, 0x9A, 0x6C, 0xF9, 0xAE, 0xA9, 0x93, 0x0B, 0x94, 0x4B, 0x7E, 0x2D, 0xB5, 0x34, 0x2B, 0x9D, 0x9E, 0x5B, 0x9F, 0xF7, 0x9A, 0xFF, 0x9A, 0x2E, 0xE1, 0x97, 0x8D, 0xD7, 0xFD, 0x01, 0xDF, 0xC5, 0x22, 0xEE, 0x02, 0x28, 0x3D, 0x3B, 0x06, 0xA9, 0xD0, 0x3A, 0xCF, 0x80, 0x96, 0x96, 0x8D, 0x7D, 0xBB, 0x0F, 0x91, 0x78, 0xFF, 0xFF, 0xFF, 0xFF, 0x02, 0x8B, 0xA7, 0x94, 0x0E, 0x00, 0x00, 0x00, 0x00, 0x19, 0x76, 0xA9, 0x14, 0xBA, 0xDE, 0xEC, 0xFD, 0xEF, 0x05, 0x07, 0x24, 0x7F, 0xC8, 0xF7, 0x42, 0x41, 0xD7, 0x3B, 0xC0, 0x39, 0x97, 0x2D, 0x7B, 0x88, 0xAC, 0x40, 0x94, 0xA8, 0x02, 0x00, 0x00, 0x00, 0x00, 0x19, 0x76, 0xA9, 0x14, 0xC1, 0x09, 0x32, 0x48, 0x3F, 0xEC, 0x93, 0xED, 0x51, 0xF5, 0xFE, 0x95, 0xE7, 0x25, 0x59, 0xF2, 0xCC, 0x70, 0x43, 0xF9, 0x88, 0xAC, 0x00, 0x00, 0x00, 0x00, 0x00]
            )
        );
        $spendTx = TransactionFactory::fromHex($spends);

        $filter = BloomFilter::create($math, 10, 0.000001, 0, BloomFilter::UPDATE_ALL);
        $filter->insertData(Buffer::hex('b4749f017444b051c44dfd2720e88f314ff94f3dd6d56d40ef65854fcd7fff6b', 32));
        $this->assertTrue($filter->isRelevantAndUpdate($tx));

        $filter = BloomFilter::create($math, 10, 0.000001, 0, BloomFilter::UPDATE_ALL);
        $filter->insertData(Buffer::hex('b4749f017444b051c44dfd2720e88f314ff94f3dd6d56d40ef65854fcd7fff6b'));
        $this->assertTrue($filter->isRelevantAndUpdate($tx));

        $filter = BloomFilter::create($math, 10, 0.000001, 0, BloomFilter::UPDATE_ALL);
        $filter->insertData(Buffer::hex('30450220070aca44506c5cef3a16ed519d7c3c39f8aab192c4e1c90d065f37b8a4af6141022100a8e160b856c2d43d27d8fba71e5aef6405b8643ac4cb7cb3c462aced7f14711a01'));
        $this->assertTrue($filter->isRelevantAndUpdate($tx));

        $filter = BloomFilter::create($math, 10, 0.000001, 0, BloomFilter::UPDATE_ALL);
        $filter->insertData(Buffer::hex('046d11fee51b0e60666d5049a9101a72741df480b96ee26488a4d3466b95c9a40ac5eeef87e10a5cd336c19a84565f80fa6c547957b7700ff4dfbdefe76036c339'));
        $this->assertTrue($filter->isRelevantAndUpdate($tx));

        $filter = BloomFilter::create($math, 10, 0.000001, 0, BloomFilter::UPDATE_ALL);
        $filter->insertData(Buffer::hex('04943fdd508053c75000106d3bc6e2754dbcff19'));
        $this->assertTrue($filter->isRelevantAndUpdate($tx));
        $this->assertTrue($filter->isRelevantAndUpdate($spendTx));

        $filter = BloomFilter::create($math, 10, 0.000001, 0, BloomFilter::UPDATE_ALL);
        $filter->insertData(Buffer::hex('a266436d2965547608b9e15d9032a7b9d64fa431'));
        $this->assertTrue($filter->isRelevantAndUpdate($tx));

        $filter = BloomFilter::create($math, 10, 0.000001, 0, BloomFilter::UPDATE_ALL);
        $filter->insertOutPoint(new OutPoint(Buffer::hex('90c122d70786e899529d71dbeba91ba216982fb6ba58f3bdaab65e73b7e9260b'), 0));
        $this->assertTrue($filter->isRelevantAndUpdate($tx));

        $filter = BloomFilter::create($math, 10, 0.000001, 0, BloomFilter::UPDATE_ALL);
        $filter->insertData(Buffer::hex('0000006d2965547608b9e15d9032a7b9d64fa431'));
        $this->assertFalse($filter->isRelevantAndUpdate($tx));

        $filter = BloomFilter::create($math, 10, 0.000001, 0, BloomFilter::UPDATE_ALL);
        $filter->insertOutPoint(new OutPoint(Buffer::hex('41c1d247b5f6ef9952cd711beba91ba216982fb6ba58f3bdaab65e7341414141'), 0));
        $this->assertFalse($filter->isRelevantAndUpdate($tx));
    }

    public function test_is_empty()
    {
        $emptyFilter = $this->getEmptyFilterVector();
        $this->assertFalse($emptyFilter->isFull());
        $this->assertTrue($emptyFilter->isEmpty());

        $fullFilter = $this->getFullFilterVector();
        $this->assertTrue($fullFilter->isFull());
        $this->assertFalse($fullFilter->isEmpty());
    }
}
