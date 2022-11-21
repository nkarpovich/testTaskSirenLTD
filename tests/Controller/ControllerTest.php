<?php
declare(strict_types=1);

namespace Siren\CommissionTask\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Siren\CommissionTask\Controller;
use Siren\CommissionTask\Exceptions\ClientNotFoundException;
use Siren\CommissionTask\Exceptions\OperationProhibitedException;
use Siren\CommissionTask\Exceptions\OperationTypeNotFoundException;

class ControllerTest extends TestCase
{

    /**
     * @var Controller
     */
    private Controller $controller;

    public function setUp():void {
        $this->controller = new Controller();
    }

    /**
     * @param array $operationsData
     * @param array $expectation
     *
     * @throws ClientNotFoundException
     * @throws OperationProhibitedException
     * @throws OperationTypeNotFoundException
     * @dataProvider dataProviderForAddTesting
     */
    public function testExecuteOperations(array $operationsData, array $expectation) {
        $this->assertEquals(
            $expectation,
            $this->controller->executeOperations($operationsData)
        );
    }

    public function dataProviderForAddTesting(): array {
        $resData = [];
        if (($handle = fopen('test.csv', "r")) !== false) {
            while (($data = fgetcsv($handle, 10000, ",")) !== false) {
                $resData[] = $data;
            }
            fclose($handle);
        }
        return [
            'First and only set of data' => [$resData, [0.6,3,0,0.06,1.5,0,1.29,0.34,0.3,3,0,0,8607.4]]
        ];
    }
}
