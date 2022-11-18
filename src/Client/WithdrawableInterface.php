<?php

namespace Siren\CommissionTask\Client;

Interface WithdrawableInterface
{
    function withdraw(float $amount, string $currency);
}