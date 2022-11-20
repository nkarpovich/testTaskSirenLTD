<?php

namespace Siren\CommissionTask\Client;

use Siren\CommissionTask\Operation\Operation;

interface DepositableInterface
{
    public function deposit(Operation $operation);
}
