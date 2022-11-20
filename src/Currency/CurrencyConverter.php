<?php

namespace Siren\CommissionTask\Currency;

use Siren\CommissionTask\Exceptions\CurrencyNotFoundException;

class CurrencyConverter
{
    /**
     * We have got here only 1 param, because task is simplified. We need to get only exchange rates FROM or TO euro.
     * @throws CurrencyNotFoundException
     */
    public static function getExchangeRate(string $from, string $to = 'EUR') {
        $url = 'https://developers.paysera.com/tasks/api/currency-exchange-rates';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);

        $exchangeRates = json_decode($data, true);
        if (isset($exchangeRates['rates'][$from])) {
            $exchangeRate = 1 / $exchangeRates['rates'][$from];
        }
        else {
            throw new CurrencyNotFoundException('Currency ' . $from . ' is not valid');
        }
        return $exchangeRate;
    }
}
