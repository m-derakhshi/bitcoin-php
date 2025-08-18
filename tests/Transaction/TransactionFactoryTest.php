<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Transaction;

use BitWasp\Bitcoin\Tests\AbstractTestCase;
use BitWasp\Bitcoin\Transaction\Factory\TxBuilder;
use BitWasp\Bitcoin\Transaction\Mutator\TxMutator;
use BitWasp\Bitcoin\Transaction\Transaction;
use BitWasp\Bitcoin\Transaction\TransactionFactory;

class TransactionFactoryTest extends AbstractTestCase
{
    public function test_builder()
    {
        $builder = TransactionFactory::build();
        $this->assertInstanceOf(TxBuilder::class, $builder);
    }

    public function test_mutate_signer()
    {
        $signer = TransactionFactory::mutate(new Transaction);
        $this->assertInstanceOf(TxMutator::class, $signer);
    }
}
