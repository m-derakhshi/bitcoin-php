<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Key\Deterministic\Slip132;

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Key\Deterministic\HdPrefix\ScriptPrefix;
use BitWasp\Bitcoin\Key\KeyToScript\KeyToScriptHelper;
use BitWasp\Bitcoin\Key\KeyToScript\ScriptDataFactory;

class Slip132
{
    /**
     * @var KeyToScriptHelper
     */
    private $helper;

    public function __construct(?KeyToScriptHelper $helper = null)
    {
        $this->helper = $helper ?: new KeyToScriptHelper(Bitcoin::getEcAdapter());
    }

    /**
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     */
    private function loadPrefix(PrefixRegistry $registry, ScriptDataFactory $factory): ScriptPrefix
    {
        [$private, $public] = $registry->getPrefixes($factory->getScriptType());

        return new ScriptPrefix($factory, $private, $public);
    }

    /**
     * xpub on bitcoin
     *
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     */
    public function p2pkh(PrefixRegistry $registry): ScriptPrefix
    {
        return $this->loadPrefix($registry, $this->helper->getP2pkhFactory());
    }

    /**
     * ypub on bitcoin
     *
     * @throws \BitWasp\Bitcoin\Exceptions\DisallowedScriptDataFactoryException
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     */
    public function p2shP2wpkh(PrefixRegistry $registry): ScriptPrefix
    {
        return $this->loadPrefix($registry, $this->helper->getP2shFactory($this->helper->getP2wpkhFactory()));
    }

    /**
     * Ypub on bitcoin
     *
     * @throws \BitWasp\Bitcoin\Exceptions\DisallowedScriptDataFactoryException
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     */
    public function p2shP2wshMultisig(int $m, int $n, bool $sortKeys, PrefixRegistry $registry): ScriptPrefix
    {
        return $this->loadPrefix($registry, $this->helper->getP2shP2wshFactory($this->helper->getMultisigFactory($m, $n, $sortKeys)));
    }

    /**
     * zpub on bitcoin
     *
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     */
    public function p2wpkh(PrefixRegistry $registry): ScriptPrefix
    {
        return $this->loadPrefix($registry, $this->helper->getP2wpkhFactory());
    }

    /**
     * Zpub on bitcoin
     *
     * @throws \BitWasp\Bitcoin\Exceptions\DisallowedScriptDataFactoryException
     * @throws \BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter
     */
    public function p2wshMultisig(int $m, int $n, bool $sortKeys, PrefixRegistry $registry): ScriptPrefix
    {
        return $this->loadPrefix($registry, $this->helper->getP2wshFactory($this->helper->getMultisigFactory($m, $n, $sortKeys)));
    }
}
