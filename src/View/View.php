<?php

namespace Siren\CommissionTask\View;

class View implements ViewInterface
{
    private array $operations;

    /**
     * @param array $operations
     */
    public function __construct(array $operations) {
        $this->operations = $operations;
    }

    /**
     * @return void
     */
    public function showFees() {
        foreach ($this->operations as $operation) {
            echo $operation->getFee() . PHP_EOL;
        }
    }
}
