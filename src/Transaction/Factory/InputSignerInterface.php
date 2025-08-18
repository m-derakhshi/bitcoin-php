<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction\Factory;

use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PrivateKeyInterface;
use BitWasp\Bitcoin\Crypto\EcAdapter\Key\PublicKeyInterface;
use BitWasp\Bitcoin\Script\FullyQualifiedScript;
use BitWasp\Bitcoin\Signature\TransactionSignatureInterface;
use BitWasp\Bitcoin\Transaction\SignatureHash\SigHash;
use BitWasp\Buffertools\BufferInterface;

interface InputSignerInterface
{
    /**
     * Calculates the signature hash for the input for the given $sigHashType.
     */
    public function getSigHash(int $sigHashType): BufferInterface;

    /**
     * Returns whether all required signatures have been provided.
     */
    public function isFullySigned(): bool;

    /**
     * Returns the required number of signatures for this input.
     */
    public function getRequiredSigs(): int;

    /**
     * Returns an array where the values are either null,
     * or a TransactionSignatureInterface.
     *
     * @return TransactionSignatureInterface[]
     */
    public function getSignatures(): array;

    /**
     * Returns an array where the values are either null,
     * or a PublicKeyInterface.
     *
     * @return PublicKeyInterface[]
     */
    public function getPublicKeys(): array;

    /**
     * OutputData for the txOut script.
     */
    public function getInputScripts(): FullyQualifiedScript;

    /**
     * @return mixed
     */
    public function getSteps();

    /**
     * @return Checksig[]|Conditional[]
     */
    public function step(int $idx);

    /**
     * @return mixed
     */
    public function signStep(int $idx, PrivateKeyInterface $privateKey, int $sigHashType = SigHash::ALL);

    /**
     * Sign the input using $key and $sigHashTypes
     *
     * @return $this
     */
    public function sign(PrivateKeyInterface $privateKey, int $sigHashType = SigHash::ALL);

    /**
     * Verifies the input using $flags for script verification, otherwise
     * uses the default, or that passed from SignData.
     */
    public function verify(?int $flags = null): bool;

    /**
     * Produces a SigValues instance containing the scriptSig & script witness
     */
    public function serializeSignatures(): SigValues;
}
