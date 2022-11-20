<?php

namespace Siren\CommissionTask\Operation;

use DateTime;
use Exception;
use Siren\CommissionTask\DataTransfer\DataTrasferInterface;

/**
 * We can use DTO factory here with different methods to create DTO
 */
class OperationDTO implements DataTrasferInterface
{
    public DateTime $date;
    public int $clientId;
    public string $clientType;
    public string $operationType;
    public float $operationAmount;
    public string $operationCurrency;

    /**
     * @throws Exception
     */
    public function __construct(array $csvDataString) {
        $this->date = new DateTime($csvDataString[0]);
        $this->clientId = $csvDataString[1];
        $this->clientType = $csvDataString[2];
        $this->operationType = $csvDataString[3];
        $this->operationAmount = $csvDataString[4];
        $this->operationCurrency = $csvDataString[5];
    }
}
