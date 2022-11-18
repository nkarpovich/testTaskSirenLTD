<?php

namespace Siren\CommissionTask\Client;

class ClientPool
{
    private array $clientInstances;

    /**
     * @throws \ClientNotFoundException
     */
    public function get(int $clientId, string $clientType){
        if(!isset($this->clientInstances[$clientId])){
            $this->clientInstances[$clientId] = ClientFactory::build($clientId,$clientType);
        }
        return $this->clientInstances[$clientId];
    }
}