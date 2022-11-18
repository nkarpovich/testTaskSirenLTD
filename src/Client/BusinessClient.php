<?php

declare(strict_types=1);

namespace Siren\CommissionTask\Client;


class BusinessClient extends Client implements WithdrawableInterface, DepositableInterface
{
    public function withdraw(float $amount, string $currency) {
        //Calculating fee
        //$this->calculateFee($amount, $currency);
        //Probably here should be some logic for actually withdrawing money...

        return $this->calculateFee('withdraw',$amount,$currency);
    }

    public function deposit(float $amount, string $currency) {
        //Probably here should be some logic for actually withdrawing money...

        //Calculate fee
        return $this->calculateFee('deposit',$amount,$currency);
    }

    public function calculateFee(string $operation, float $amount, string $currency) {
        return 0;
    }
}