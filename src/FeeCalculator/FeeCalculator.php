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
    public const DEPOSIT_CHARGE = 0.03 / 100;
    public const PRIVATE_CLIENTS_FREE_OF_CHARGE_SUM = 1000;
    public const PRIVATE_CLIENTS_WITHDRAW_CHARGE = 0.3 / 100;
    public const BUSINESS_CLIENTS_WITHDRAW_CHARGE = 0.5 / 100;
    public const WEEKLY_OPERATIONS_WITHOUT_COMMISSION = 3;

    private array $clientOperationList;
    private Operation $operation;

    /**
     * Client operation list should be sorted by insertion date
     * @param array $clientOperationList
     * @param Operation $operation
     */
    public function __construct(array $clientOperationList, Operation $operation)
    {
        //We need operations that were made earlier
        $this->clientOperationList = $clientOperationList;
        $this->operation = $operation;
    }

    /**
     * @return float|int
     * @throws CurrencyNotFoundException
     */
    public function calculateFee()
    {
        $feeInEuro = 0;
        if ($this->operation->getOperationType() === 'deposit') {
            $feeInEuro = $this->operation->getOperationAmount() * self::DEPOSIT_CHARGE;
        } elseif ($this->operation->getOperationType() === 'withdraw') {
            if ($this->operation->getClientType() === 'business') {
                $feeInEuro = $this->operation->getOperationAmount() * self::BUSINESS_CLIENTS_WITHDRAW_CHARGE;
            } elseif ($this->operation->getClientType() === 'private') {
                $feeInEuro = $this->calculateFeeForPrivateWithdraw();
            }
        }
        if ($this->operation->getOperationCurrency()!=='EUR') {
            $fee = (1/CurrencyConverter::getExchangeRate($this->operation->getOperationCurrency()))*$feeInEuro;
        } else {
            $fee = $feeInEuro;
        }
        return $this->feeRound($fee);
    }

    private function feeRound($fee)
    {
        return $this->roudUp($fee, 2);
    }

    private function roudUp($value, $places = 0)
    {
        if ($places < 0) {
            $places = 0;
        }
        $mult = pow(10, $places);
        return ceil($value * $mult) / $mult;
    }

    /**
     * @throws CurrencyNotFoundException
     */
    private function calculateFeeForPrivateWithdraw()
    {
        $fee = 0;
        $weeklyOperationsCount = $this->getWeeklyOperationsCount();
        $weeklySumEuro = $this->getWeeklySumInEuro();
        if ($weeklyOperationsCount >= self::WEEKLY_OPERATIONS_WITHOUT_COMMISSION || $weeklySumEuro >= self::PRIVATE_CLIENTS_FREE_OF_CHARGE_SUM) {
            $fee = $this->operation->getOperationAmount() * self::PRIVATE_CLIENTS_WITHDRAW_CHARGE;
        } else {
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

    private function getWeeklyOperationsCount(): int
    {
        return count($this->getOperationsForCurrenWeek());
    }

    private function getOperationsForCurrenWeek(): array
    {
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
    private function getWeeklySumInEuro()
    {
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

    private function convertToEuro(string $currency, float $val)
    {
        if ($currency === 'EUR') {
            return $val;
        } else {
            $rate = CurrencyConverter::getExchangeRate($currency);
            return $rate * $val;
        }
    }
}
