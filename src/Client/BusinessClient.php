<?php

declare(strict_types=1);

namespace Siren\CommissionTask\Client;


class BusinessClient extends Client implements WithdrawableInterface, DepositableInterface
{
    public function withdraw(float $amount, string $currency) {
        return $this->calculateFee('withdraw',$amount,$currency);
    }

    public function deposit(float $amount, string $currency) {
        return $this->calculateFee('deposit',$amount,$currency);
    }

    public function calculateFee(string $operation, float $amount, string $currency) {
        return 0;
    }
}