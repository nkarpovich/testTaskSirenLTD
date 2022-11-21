<?php

declare(strict_types=1);

namespace Siren\CommissionTask\Client;

use Siren\CommissionTask\Exceptions\CurrencyNotFoundException;
use Siren\CommissionTask\FeeCalculator\FeeCalculator;
use Siren\CommissionTask\Operation\Operation;

class BusinessClient extends Client implements WithdrawableInterface, DepositableInterface
{
    public function withdraw(Operation $operation) {
        $this->doOperation($operation);
    }

    public function deposit(Operation $operation) {
        $this->doOperation($operation);
    }

    /**
     * @throws CurrencyNotFoundException
     */
    public function applyFee(Operation &$operation) {
        $feeCalculator = new FeeCalculator($this->getOperationsHistory(), $operation);
        $fee = $feeCalculator->calculateFee();
        $operation->setFee($fee);
    }
}
