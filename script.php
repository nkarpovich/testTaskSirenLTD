<?php

use Siren\CommissionTask\Controller;
use Siren\CommissionTask\Input\CsvInput;

require 'vendor/autoload.php';

$filePath = $argv[1];
try {
    $input = new CsvInput($filePath);
    $clientsOperations = $input->getInputData();
    $controller = new Controller();
    $controller->executeOperations($clientsOperations);
} catch (Throwable $e) {
    echo 'Error: ' . $e->getMessage();
}