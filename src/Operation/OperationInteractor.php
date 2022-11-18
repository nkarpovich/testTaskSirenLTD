<?php

namespace Siren\CommissionTask\Operation;

use Siren\CommissionTask\Client\ClientPool;

class OperationInteractor
{
    private OperationInputInterface $operationData;

    public function __construct(OperationInputInterface $operation){
        $this->operationData = $operation;
    }

    /**
     * @throws \ClientNotFoundException
     */
    public function executeOperation(){
        $clientPool = new ClientPool();
        $client = $clientPool->get($this->operationData->clientId,$this->operationData->clientType);
    }
}