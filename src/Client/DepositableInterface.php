<?php

namespace Siren\CommissionTask\Client;

use Siren\CommissionTask\Operation\Operation;

Interface DepositableInterface
{
    function deposit(Operation $operation);
}