<?php

namespace Siren\CommissionTask\DataAccess;

class OperationDataAccess implements DataAccessInterface
{
    /**
     * @var array
     */
    private array $operations;

    /**
     * @return array
     */
    public function get()
    {
        return $this->operations;
    }

    /**
     * @param array $data
     * @return void
     */
    public function add(array $data)
    {
        $this->operations[] = $data;
    }
}
