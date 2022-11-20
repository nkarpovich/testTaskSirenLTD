<?php

namespace Siren\CommissionTask\DataAccess;

interface DataAccessInterface
{
    public function get();

    public function add(array $data);
}