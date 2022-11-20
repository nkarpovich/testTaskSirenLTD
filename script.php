<?php

use Siren\CommissionTask\Controller;
use Siren\CommissionTask\Input\CsvInput;

require 'vendor/autoload.php';

try {
    //simplification of console input and input in general
    $filePath = $argv[1];

    //Get operations data
    $input = new CsvInput($filePath);
    $clientsOperations = $input->getInputData();

    //Pass data to controller
    $controller = new Controller();
    $controller->executeOperations($clientsOperations);
} catch (Throwable $e) {
    echo 'Error: ' . $e->getMessage();
}