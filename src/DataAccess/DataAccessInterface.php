<?php

namespace Siren\CommissionTask\DataAccess;

interface DataAccessInterface
{
    public function get();

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);
}
