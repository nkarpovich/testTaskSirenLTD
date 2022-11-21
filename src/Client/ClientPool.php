<?php

namespace Siren\CommissionTask\Client;

use Siren\CommissionTask\Exceptions\ClientNotFoundException;

/**
 * Pool pattern. Used to get instances that are already created and stored in the pool.
 */
class ClientPool
{
    private array $clientInstances = [];

    /**
     * @throws ClientNotFoundException
     */
    public function get(int $clientId, string $clientType) {
        if (!isset($this->clientInstances[$clientId])) {
            $this->clientInstances[$clientId] = ClientFactory::build($clientId, $clientType);
        }
        return $this->clientInstances[$clientId];
    }
}
