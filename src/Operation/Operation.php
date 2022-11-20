<?php

namespace Siren\CommissionTask\Operation;

use DateTime;
use Siren\CommissionTask\DataTransfer\DataTrasferInterface;

class Operation
{
    private DateTime $date;
    private int $clientId;
    private string $clientType;
    private string $operationType;
    private float $operationAmount;
    private string $operationCurrency;
    private float $fee;

    /**
     * @param DataTrasferInterface $operationDTO
     */
    public function __construct(DataTrasferInterface $operationDTO) {
        $this->date = $operationDTO->date;
        $this->clientId = $operationDTO->clientId;
        $this->clientType = $operationDTO->clientType;
        $this->operationType = $operationDTO->operationType;
        $this->operationAmount = $operationDTO->operationAmount;
        $this->operationCurrency = $operationDTO->operationCurrency;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getClientId(): int {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientType(): string {
        return $this->clientType;
    }

    /**
     * @return string
     */
    public function getOperationType(): string {
        return $this->operationType;
    }

    /**
     * @return float
     */
    public function getOperationAmount(): float {
        return $this->operationAmount;
    }

    /**
     * @return string
     */
    public function getOperationCurrency(): string {
        return $this->operationCurrency;
    }

    public function getFee(): float {
        return $this->fee;
    }

    public function setFee(float $fee) {
        $this->fee = $fee;
    }
}
