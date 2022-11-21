<?php

namespace Siren\CommissionTask;

/*
 * Controller is simplified
 */

use Siren\CommissionTask\Operation\OperationDTO;
use Siren\CommissionTask\Operation\OperationInteractor;

class Controller
{
    /**
     * @param array $operationsData
     * @return array
     * @throws Exceptions\ClientNotFoundException
     * @throws Exceptions\OperationProhibitedException
     * @throws Exceptions\OperationTypeNotFoundException
     * @throws \Exception
     */
    public function executeOperations(array $operationsData): array {
        $operationsDTO = [];
        foreach ($operationsData as $operation) {
            $operationsDTO[] = new OperationDTO($operation);
        }
        $OperationInteractor = new OperationInteractor($operationsDTO);
        $operations = $OperationInteractor->executeOperations();
        return $operations;
    }
}
