<?php

namespace Siren\CommissionTask\Client;

use Siren\CommissionTask\Exceptions\CurrencyNotFoundException;
use Siren\CommissionTask\FeeCalculator\FeeCalculator;
use Siren\CommissionTask\Operation\Operation;

class PrivateClient extends Client implements WithdrawableInterface, DepositableInterface
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
    function applyFee(Operation &$operation) {
        //TODO not quite good solution, bad dependency
        $feeCalculator = new FeeCalculator($this->getOperationsHistory(),$operation);
        $fee = $feeCalculator->calculateFee();
        $operation->setFee($fee);
        echo $operation->getFee().PHP_EOL;
    }
}