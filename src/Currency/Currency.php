<?php

namespace Siren\CommissionTask\Currency;

class Currency
{
    private string $currencyCode;
    private float $amount;

    public function __construct(string $currencyCode, float $amount) {
        $this->setCurrencyCode($currencyCode);
        $this->setAmount($amount);
    }

    public function convertToCurrency(string $currencyCodeConvertTo): Currency {
//        $ratio = $this->getCurrency
        $ratio = 0.5;
        $newAmount = $this->getAmount() * $ratio;
        return new self($currencyCodeConvertTo, $newAmount);
    }

    /**
     * @return float
     */
    public function getAmount(): float {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode(string $currencyCode): void {
        $this->currencyCode = $currencyCode;
    }
}