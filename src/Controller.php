<?php

namespace Siren\CommissionTask;

/*
 * Controller is simplified
 */

use Siren\CommissionTask\Operation\OperationDTO;
use Siren\CommissionTask\Operation\OperationInteractor;

class Controller
{
    public function executeOperations($operationsData){
        foreach ($operationsData as $operation) {
            $dto = new OperationDTO($operation);
            $OperationInteractor = new OperationInteractor($dto);
            $OperationInteractor->executeOperation();
        }
    }
}