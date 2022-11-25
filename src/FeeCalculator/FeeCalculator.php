<?php

namespace Siren\CommissionTask\FeeCalculator;

use Siren\CommissionTask\Currency\CurrencyConverter;
use Siren\CommissionTask\Exceptions\CurrencyNotFoundException;
use Siren\CommissionTask\Operation\Operation;

/**
 * We can use fabric here to get specific calculators for different user types
 */
class FeeCalculator
{
    private const DEPOSIT_CHARGE = 0.03 / 100;
    private const PRIVATE_CLIENTS_FREE_OF_CHARGE_SUM = 1000;
    private const PRIVATE_CLIENTS_WITHDRAW_CHARGE = 0.3 / 100;
    private const BUSINESS_CLIENTS_WITHDRAW_CHARGE = 0.5 / 100;
    private const WEEKLY_OPERATIONS_WITHOUT_COMMISSION = 3;

    /**
     * We need all previous client operations for calculations
     * @var array
     */
    private array $clientOperationList;

    /**
     * Current operation for calculations
     * @var Operation
     */
    private Operation $operation;

    /**
     * @param array $clientOperationList
     * @param Operation $operation
     */
    public function __construct(array $clientOperationList, Operation $operation) {
        $this->clientOperationList = $clientOperationList;
        $this->operation = $operation;
    }

    /**
     * @return float|int
     * @throws CurrencyNotFoundException
     */
    public function getFormattedFee() {
        $fee = $this->calculateFee();
        if ($this->operation->getOperationCurrency() !== 'EUR') {
            $fee = $this->covertFeeToEuro($fee);
        }
        return $this->feeRound($fee);
    }

    /**
     * @return float|int
     * @throws CurrencyNotFoundException
     */
    public function calculateFee() {
        $fee = 0;
        if ($this->operation->getOperationType() === 'deposit') {
            $fee = $this->calculateFeeForDeposit();
        }
        if ($this->operation->getOperationType() === 'withdraw') {
            if ($this->operation->getClientType() === 'business') {
                $fee = $this->calculateFeeForBusinessWithdraw();
            }
            elseif ($this->operation->getClientType() === 'private') {
                $fee = $this->calculateFeeForPrivateWithdraw();
            }
        }
        return $fee;
    }

    /**
     * @return float|int
     */
    private function calculateFeeForDeposit() {
        return $this->operation->getOperationAmount() * self::DEPOSIT_CHARGE;
    }

    /**
     * @return float|int
     */
    private function calculateFeeForBusinessWithdraw() {
        return $this->operation->getOperationAmount() * self::BUSINESS_CLIENTS_WITHDRAW_CHARGE;
    }

    /**
     * @throws CurrencyNotFoundException
     */
    private function calculateFeeForPrivateWithdraw() {
        $fee = 0;
        $weeklyOperationsCount = $this->getWeeklyOperationsCount();
        $weeklySumEuro = $this->getWeeklySumInEuro();
        if ($weeklyOperationsCount >= self::WEEKLY_OPERATIONS_WITHOUT_COMMISSION || $weeklySumEuro >= self::PRIVATE_CLIENTS_FREE_OF_CHARGE_SUM) {
            $fee = $this->operation->getOperationAmount() * self::PRIVATE_CLIENTS_WITHDRAW_CHARGE;
        }
        else {
            $totalSum = $weeklySumEuro + $this->convertToEuro(
                    $this->operation->getOperationCurrency(),
                    $this->operation->getOperationAmount()
                );
            if (($totalSum) > self::PRIVATE_CLIENTS_FREE_OF_CHARGE_SUM) {
                $fee = ($totalSum - self::PRIVATE_CLIENTS_FREE_OF_CHARGE_SUM) *
                    self::PRIVATE_CLIENTS_WITHDRAW_CHARGE;
            }
        }
        return $fee;
    }

    /**
     * @return int
     */
    private function getWeeklyOperationsCount(): int {
        return count($this->getOperationsForCurrenWeek());
    }

    /**
     * @return array
     */
    private function getOperationsForCurrenWeek(): array {
        $operations = [];
        $monday = clone $this->operation->getDate();
        $monday->modify('monday this week');

        $sunday = clone $this->operation->getDate();
        $sunday->modify('sunday this week');
        foreach ($this->clientOperationList as $operation) {
            //If date is on the week of operation
            if ($operation->getDate() >= $monday && $operation->getDate() <= $sunday) {
                $operations[] = $operation;
            }
        }
        return $operations;
    }

    /**
     * @throws CurrencyNotFoundException
     */
    private function getWeeklySumInEuro() {
        $weeklySum = 0;
        $weeklyOperations = $this->getOperationsForCurrenWeek();
        if (count($weeklyOperations) > 0) {
            foreach ($weeklyOperations as $operation) {
                $amountInEuro = $this->convertToEuro(
                    $operation->getOperationCurrency(),
                    $operation->getOperationAmount()
                );
                $weeklySum += $amountInEuro;
            }
        }
        return $weeklySum;
    }

    /**
     * @param string $currency
     * @param float $val
     * @return float|int
     * @throws CurrencyNotFoundException
     */
    private function convertToEuro(string $currency, float $val) {
        if ($currency === 'EUR') {
            return $val;
        }
        else {
            $rate = CurrencyConverter::getExchangeRate($currency);
            return $rate * $val;
        }
    }

    /**
     * @param $fee
     * @return float|int
     * @throws CurrencyNotFoundException
     */
    public function covertFeeToEuro($fee) {
        return (1 / CurrencyConverter::getExchangeRate($this->operation->getOperationCurrency())) * $fee;
    }

    /**
     * @param $fee
     * @return float|int
     */
    private function feeRound($fee) {
        return $this->roudUp($fee, 2);
    }

    /**
     * @param $value
     * @param $places
     * @return float|int
     */
    private function roudUp($value, $places = 0) {
        if ($places < 0) {
            $places = 0;
        }
        $mult = pow(10, $places);
        return ceil($value * $mult) / $mult;
    }
}
