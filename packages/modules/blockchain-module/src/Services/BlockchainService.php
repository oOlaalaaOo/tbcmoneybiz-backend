<?php

namespace Modules\BlockchainModule\Services;

class BlockchainService
{
    public static function convertUsdToBtc($usd_amount = 0)
    {
        $url = 'https://blockchain.info/tobtc?' . http_build_query([
            'currency'  => 'USD',
            'value'     => $usd_amount
        ]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        curl_close($ch);

        if (!$response) {
            return 0;
        }

        return $response;
    }
}
