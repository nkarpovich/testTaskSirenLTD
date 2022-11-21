<?php

namespace Siren\CommissionTask\Operation;

use Siren\CommissionTask\Client\ClientPool;
use Siren\CommissionTask\Client\DepositableInterface;
use Siren\CommissionTask\Client\WithdrawableInterface;
use Siren\CommissionTask\Exceptions\ClientNotFoundException;
use Siren\CommissionTask\Exceptions\OperationProhibitedException;
use Siren\CommissionTask\Exceptions\OperationTypeNotFoundException;

class OperationInteractor
{
    private array $operationsData;

    public function __construct(array $operationsData) {
        $this->operationsData = $operationsData;
    }

    /**
     * @throws ClientNotFoundException
     * @throws OperationProhibitedException
     * @throws OperationTypeNotFoundException
     */
    public function executeOperations(): array {
        $executedOperations = [];
        $clientPool = new ClientPool();
        foreach ($this->operationsData as $operationDTO) {
            $executedOperations[] = $this->executeOperation($clientPool, $operationDTO);
        }
        return $executedOperations;
    }

    /**
     * @throws OperationTypeNotFoundException
     * @throws OperationProhibitedException
     * @throws ClientNotFoundException
     */
    public function executeOperation(ClientPool $clientPool, OperationDTO $operationDTO): Operation {
        $client = $clientPool->get($operationDTO->clientId, $operationDTO->clientType);
        $operation = new Operation($operationDTO);
        switch ($operation->getOperationType()) {
            case 'deposit':
                if ($client instanceof DepositableInterface) {
                    $client->deposit($operation);
                }
                else {
                    throw new OperationProhibitedException('Operation type is not supported by this type of client');
                }
                break;
            case 'withdraw':
                if ($client instanceof WithdrawableInterface) {
                    $client->withdraw($operation);
                }
                else {
                    throw new OperationProhibitedException('Operation type is not supported by this type of client');
                }
                break;
            default:
                throw new OperationTypeNotFoundException('Operation type ' . $operation->getOperationType() . ' is not supported by the system');
        }
        return $operation;
    }
}
