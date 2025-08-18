<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin;

use BitWasp\Bitcoin\Address\AddressInterface;
use BitWasp\Bitcoin\Network\NetworkInterface;

class Uri
{
    const BIP0021 = 0;

    const BIP0072 = 1;

    /**
     * @var AddressInterface
     */
    private $bip21Address;

    /**
     * @var AddressInterface|null
     */
    private $bip72Address;

    /**
     * @var null|string
     */
    private $amount;

    /**
     * @var string|null
     */
    private $label;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $request;

    /**
     * @var int
     */
    private $rule;

    /**
     * Uri constructor.
     */
    public function __construct(?AddressInterface $address = null, int $convention = self::BIP0021)
    {
        if ($convention === self::BIP0021) {
            if ($address === null) {
                throw new \InvalidArgumentException('Cannot provide a null address with bip0021');
            }
            $this->bip21Address = $address;
        } elseif ($convention === self::BIP0072) {
            $this->bip72Address = $address;
        } else {
            throw new \InvalidArgumentException('Invalid convention for bitcoin uri');
        }

        $this->rule = $convention;
    }

    /**
     * @return $this
     */
    public function setAmountBtc(string $value)
    {
        $this->amount = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function setAmount(Amount $amount, int $value)
    {
        $this->amount = $amount->toBtc($value);

        return $this;
    }

    /**
     * @return $this
     */
    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return $this
     */
    public function setRequestUrl(string $url)
    {
        $this->request = $url;

        return $this;
    }

    public function uri(?NetworkInterface $network = null): string
    {
        if ($this->rule === self::BIP0072) {
            $address = $this->bip72Address === null ? '' : $this->bip72Address->getAddress($network);
        } else {
            $address = $this->bip21Address->getAddress($network);
        }

        $url = 'bitcoin:'.$address;

        $params = [];
        if ($this->amount !== null) {
            $params['amount'] = $this->amount;
        }

        if ($this->label !== null) {
            $params['label'] = $this->label;
        }

        if ($this->message !== null) {
            $params['message'] = $this->message;
        }

        if ($this->request !== null) {
            $params['r'] = $this->request;
        }

        if (count($params) > 0) {
            $url .= '?'.http_build_query($params);
        }

        return $url;
    }
}
