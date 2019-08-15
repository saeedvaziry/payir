<?php


namespace SaeedVaziry\Payir\Http;

class Request
{
    /**
     * Make http request to Pay.ir
     *
     * @param $url
     * @param array $params
     * @return array
     */
    public static function make($url, $params = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);

            return null;
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return [
            'status' => $status,
            'response' => json_decode($response, true),
        ];
    }
}