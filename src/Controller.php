<?php

namespace Siren\CommissionTask;

/*
 * Controller is simplified
 */

use Exception;
use Siren\CommissionTask\Client\ClientPool;
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
     */
    public function executeOperations(array $operationsData): array {
        $fees = [];
        $clientPool = new ClientPool();
        foreach ($operationsData as $operation) {
            $dto = new OperationDTO($operation);
            $OperationInteractor = new OperationInteractor($dto);
            $fee = $OperationInteractor->executeOperation($clientPool);
            $fees[] = $fee;
        }
        return $fees;
    }
}
