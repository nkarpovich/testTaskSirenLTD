<?php

namespace Siren\CommissionTask\Client;

use Siren\CommissionTask\Exceptions\ClientNotFoundException;

class ClientFactory
{
    /**
     * @throws ClientNotFoundException
     */
    public static function build(int $clientId, string $clientType)
    {
        $className = 'Siren\CommissionTask\Client\\' . ucfirst($clientType).'Client';
        if (class_exists($className)) {
            return new  $className($clientId);
        } else {
            throw new ClientNotFoundException('Wrong client type: '.$clientType);
        }
    }
}
