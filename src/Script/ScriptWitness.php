<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Script;

use BitWasp\Bitcoin\Collection\StaticBufferCollection;
use BitWasp\Bitcoin\Serializer\Script\ScriptWitnessSerializer;
use BitWasp\Buffertools\BufferInterface;

class ScriptWitness extends StaticBufferCollection implements ScriptWitnessInterface
{
    public function equals(ScriptWitnessInterface $witness): bool
    {
        $nStack = count($this);
        if ($nStack !== count($witness)) {
            return false;
        }

        for ($i = 0; $i < $nStack; $i++) {
            if ($this->offsetGet($i)->equals($witness->offsetGet($i)) === false) {
                return false;
            }
        }

        return true;
    }

    public function getBuffer(): BufferInterface
    {
        return (new ScriptWitnessSerializer)->serialize($this);
    }
}
