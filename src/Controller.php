<?php

namespace Siren\CommissionTask;

/*
 * Controller is simplified
 */

use Siren\CommissionTask\Input\InputInterface;
use Siren\CommissionTask\Operation\OperationDTO;
use Siren\CommissionTask\Operation\OperationInteractor;

class Controller
{
    private InputInterface $input;

    /**
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input) {
        $this->input = $input;
    }

    /**
     * @return array
     * @throws Exceptions\ClientNotFoundException
     * @throws Exceptions\OperationProhibitedException
     * @throws Exceptions\OperationTypeNotFoundException
     */
    public function executeOperations(): array {
        $operationsInputData = $this->input->getData();
        $operationsDTO = [];
        foreach ($operationsInputData as $operation) {
            $operationsDTO[] = new OperationDTO($operation);
        }
        $OperationInteractor = new OperationInteractor($operationsDTO);
        $operations = $OperationInteractor->executeOperations();
        return $operations;
    }
}
