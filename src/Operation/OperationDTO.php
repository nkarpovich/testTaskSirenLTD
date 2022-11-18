<?php

namespace Siren\CommissionTask\Operation;

/**
 * We can use DTO factory here with different methods to create DTO
 */
class OperationDTO implements OperationInputInterface
{
    public string $date;
    public int $clientId;
    public string $clientType;
    public string $operationType;
    public float $operationAmount;
    public string $operationCurrency;

    public function __construct(array $csvDataString){
        $this->date = $csvDataString[0];
        $this->clientId = $csvDataString[1];
        $this->clientType = $csvDataString[2];
        $this->operationType = $csvDataString[3];
        $this->operationAmount = $csvDataString[4];
        $this->operationCurrency = $csvDataString[5];
    }
}