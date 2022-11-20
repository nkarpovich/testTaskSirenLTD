<?php

namespace Siren\CommissionTask\Client;

use Siren\CommissionTask\Operation\Operation;

interface WithdrawableInterface
{
    public function withdraw(Operation $operation);
}
