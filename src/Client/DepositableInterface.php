<?php

namespace Siren\CommissionTask\Client;

use Siren\CommissionTask\OperationRules\RulesInterface;

Interface DepositableInterface
{
    function deposit(float $amount, string $currency);
}