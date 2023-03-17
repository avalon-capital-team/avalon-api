<?php

namespace App\Helpers;

class CryptoAmountHelper
{
    /**
     * @param string $coinSymbol
     * @param string $fee
     * @param float $amount
     * @return float
     */
    public static function convert($coinSymbol, $type, $amount)
    {
        if (in_array($coinSymbol, ['ETH', 'BNB'])) {
            if ($type == 'fee') {
                return $amount / 1000000000000000000;
            } elseif ($coinSymbol == 'BNB') {
                return $amount / 1000000000;
            }
        } elseif ($coinSymbol == 'BTC') {
            return $amount / 100000000;
        }

        return $amount;
    }

    /**
     * Convert amount received from API
     *
     * @param  string $coinSymbol
     * @param  string $type
     * @param  float $amount
     * @return float
     */
    public static function convertFromApi($coinSymbol, $type, $amount)
    {
        if ($type == 'trc20') {
            if ($coinSymbol == 'USDT') {
                return $amount / 1000000;
            }
        }

        return $amount;
    }
}
