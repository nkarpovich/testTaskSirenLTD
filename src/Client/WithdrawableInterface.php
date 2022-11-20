<?php

namespace Siren\CommissionTask\Client;

use Siren\CommissionTask\Operation\Operation;

Interface WithdrawableInterface
{
    function withdraw(Operation $operation);
}