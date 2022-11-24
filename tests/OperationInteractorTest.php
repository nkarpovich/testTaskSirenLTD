<?php
declare(strict_types=1);

namespace Siren\CommissionTask\Tests;

use PHPUnit\Framework\TestCase;
use Siren\CommissionTask\Client\ClientPool;
use Siren\CommissionTask\Exceptions\ClientNotFoundException;
use Siren\CommissionTask\Exceptions\OperationProhibitedException;
use Siren\CommissionTask\Exceptions\OperationTypeNotFoundException;
use Siren\CommissionTask\Operation\OperationDTO;
use Siren\CommissionTask\Operation\OperationInteractor;

class OperationInteractorTest extends TestCase
{
    private const TEST_OPERATIONS_FILE = 'test.csv';

    private OperationInteractor $operationInteractor;

    /**
     * @throws \Exception
     */
    public function setUp():void {
        $operations = [];
        if (($handle = fopen(self::TEST_OPERATIONS_FILE, "r")) !== false) {
            while (($data = fgetcsv($handle, 10000, ",")) !== false) {
                $operations[] = $data;
            }
            fclose($handle);
        }
        $operationsDTO = [];
        foreach ($operations as $operation){
            $operationsDTO[] = new OperationDTO($operation);
        }
        $this->clientPool = new ClientPool();
        $this->operationInteractor = new OperationInteractor($operationsDTO);
    }

    /**
     * @throws ClientNotFoundException
     * @throws OperationProhibitedException
     * @throws OperationTypeNotFoundException
     */
    public function testExecuteOperations() {
        $operations = $this->operationInteractor->executeOperations();
        $arFees = [];
        foreach ($operations as $operation) {
            $arFees[] = $operation->getFee();
        }
        $this->assertEquals(
            [0.6,3,0,0.06,1.5,0,1.29,0.34,0.3,3,0,0,8607.4],
            $arFees
        );
    }
}
