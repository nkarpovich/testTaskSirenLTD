<?php

namespace Siren\CommissionTask\Client;

use Siren\CommissionTask\Exceptions\CurrencyNotFoundException;
use Siren\CommissionTask\FeeCalculator\FeeCalculator;
use Siren\CommissionTask\Operation\Operation;

class PrivateClient extends Client implements WithdrawableInterface, DepositableInterface
{
    /**
     * @param Operation $operation
     * @return void
     */
    public function withdraw(Operation $operation) {
        $this->doOperation($operation);
    }

    /**
     * @param Operation $operation
     * @return void
     */
    public function deposit(Operation $operation) {
        $this->doOperation($operation);
    }

    /**
     * @throws CurrencyNotFoundException
     */
    public function applyFee(Operation &$operation) {
        $feeCalculator = new FeeCalculator($this->getOperationsHistory(), $operation);
        $fee = $feeCalculator->getFormattedFee();
        $operation->setFee($fee);
    }
}
