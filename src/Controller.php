<?php

namespace Siren\CommissionTask;

/*
 * Controller is simplified
 */

use Siren\CommissionTask\Client\ClientPool;
use Siren\CommissionTask\Operation\OperationDTO;
use Siren\CommissionTask\Operation\OperationInteractor;

class Controller
{
    /**
     * @throws \Exception
     */
    public function executeOperations(array $operationsData){
        $clientPool = new ClientPool();
        foreach ($operationsData as $operation) {
            $dto = new OperationDTO($operation);
            $OperationInteractor = new OperationInteractor($dto);
            $OperationInteractor->executeOperation($clientPool);
        }
    }
}