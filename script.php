<?php

use Siren\CommissionTask\Controller;
use Siren\CommissionTask\Input\CsvInput;
use Siren\CommissionTask\View\View;

require 'vendor/autoload.php';

try {
    //simplification of console input
    $filePath = $argv[1];

    $input = new CsvInput($filePath);

    //Pass input to controller
    $controller = new Controller($input);
    $operations = $controller->executeOperations();

    //View object prints fees
    $view = new View($operations);
    $view->showFees();
} catch (Throwable $e) {
    echo 'Error: ' . $e->getMessage();
}