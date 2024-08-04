<?php

namespace App\Service;

use GuzzleHttp\Client;

class CheckUserAuthService
{
    static function checkUserAuth($token)
    {
        try {
            if (!$token) {
                return null;
            }
            $endpoint = env('AUTH_SERVICE_URL') . env('AUTH_SERVICE_CHECK_TOKEN_URL');
            // set timeout for 2 seconds
            $client = new Client(['timeout' => 10.0]);
            $response = $client->request('GET', $endpoint, [
                'headers' => [
                    'Authorization' 
                    => 'Bearer ' . $token,
                    'Accept' => 'application/json'
                ]
            ]);
            if ($response->getStatusCode() !== 200) {
                return null;
            }
            $responseBody = json_decode($response->getBody()->getContents(), true);
            return $responseBody['user']['id'];
        } catch (\Exception $e) {
            return null;
        }
    }
}
