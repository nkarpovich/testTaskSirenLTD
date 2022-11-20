<?php

namespace Siren\CommissionTask\Operation;

use Siren\CommissionTask\Client\ClientPool;
use Siren\CommissionTask\Client\DepositableInterface;
use Siren\CommissionTask\Client\WithdrawableInterface;
use Siren\CommissionTask\DataTransfer\DataTrasferInterface;
use Siren\CommissionTask\Exceptions\ClientNotFoundException;
use Siren\CommissionTask\Exceptions\OperationProhibitedException;
use Siren\CommissionTask\Exceptions\OperationTypeNotFoundException;

class OperationInteractor
{
    private DataTrasferInterface $operationData;

    public function __construct(DataTrasferInterface $operation) {
        $this->operationData = $operation;
    }

    /**
     * @throws OperationTypeNotFoundException
     * @throws OperationProhibitedException
     * @throws ClientNotFoundException
     */
    public function executeOperation(ClientPool $clientPool) {
        //TODO - remove ClientPool from arguments, dependency should be removed
        $client = $clientPool->get($this->operationData->clientId, $this->operationData->clientType);
        $operation = new Operation($this->operationData);
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
    }
}
