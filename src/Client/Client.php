<?php

namespace Siren\CommissionTask\Client;

use Siren\CommissionTask\Operation\Operation;

abstract class Client
{
    protected int $clientId;
    protected array $operations = [];

    public function __construct($clientId) {
        $this->clientId = $clientId;
    }
    public function getOperationsHistory(): array {
        return $this->operations;
    }
    function doOperation(Operation $operation){
        $this->applyFee($operation);
        $this->operations[] = $operation;
    }
    abstract function applyFee(Operation &$operation);
}