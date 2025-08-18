<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Network;

use BitWasp\Bitcoin\Network\Networks\Bitcoin;
use BitWasp\Bitcoin\Tests\AbstractTestCase;

class SignedMessageMagicTest extends AbstractTestCase
{
    public function test_get_signed_message_magic()
    {
        $bitcoin = new Bitcoin;
        $this->assertEquals('Bitcoin Signed Message', $bitcoin->getSignedMessageMagic());
    }
}
