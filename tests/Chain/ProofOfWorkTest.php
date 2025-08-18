<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Chain;

use BitWasp\Bitcoin\Block\BlockHeader;
use BitWasp\Bitcoin\Block\BlockHeaderInterface;
use BitWasp\Bitcoin\Chain\Params;
use BitWasp\Bitcoin\Chain\ProofOfWork;
use BitWasp\Bitcoin\Math\Math;
use BitWasp\Bitcoin\Tests\AbstractTestCase;
use BitWasp\Buffertools\Buffer;

class ProofOfWorkTest extends AbstractTestCase
{
    public function getHistoricData()
    {
        $math = $this->safeMath();
        $params = new Params($math);
        $pow = new ProofOfWork(new Math, $params);
        $data = json_decode($this->dataFile('pow'), true);

        $results = [];
        foreach ($data as $c => $record) {
            [$height, $hash, $version, $prev, $merkle, $time, $bits, $nonce] = $record;
            $header = new BlockHeader($version, Buffer::hex($prev, 32), Buffer::hex($merkle, 32), (int) $time, (int) Buffer::hex($bits)->getInt(), (int) $nonce);
            $results[] = [$pow, $height, $hash, $header];
        }

        return $results;
    }

    /**
     * @expectedException \RuntimeException
     *
     * @expectedExceptionMessage nBits below minimum work
     */
    public function test_where_bits_below_minimum()
    {
        $math = $this->safeMath();
        $params = new Params($math);
        $pow = new ProofOfWork(new Math, $params);
        $bits = 1;
        $pow->checkPow(Buffer::hex('00000000a3bbe4fd1da16a29dbdaba01cc35d6fc74ee17f794cf3aab94f7aaa0'), $bits);
    }

    public function test_where_hash_too_low()
    {
        $math = new Math;
        $params = new Params($math);
        $pow = new ProofOfWork(new Math, $params);
        $bits = 0x181287BA;
        $this->assertFalse($pow->checkPow(Buffer::hex('00000000a3bbe4fd1da16a29dbdaba01cc35d6fc74ee17f794cf3aab94f7aaa0'), $bits));
    }

    /**
     * @dataProvider getHistoricData
     *
     * @param  int  $height
     * @param  string  $hash
     */
    public function test_historic(ProofOfWork $pow, $height, $hash, BlockHeaderInterface $header)
    {
        $this->assertTrue($pow->checkHeader($header));
    }
}
