<?php namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Exception;

trait APIRequestCommon
{
    public function sendRequest($path, $method, $parameters = [], $headers = [])
    {
        $client = new Client();
        try {
            if (empty($headers)) {
                $res = $client->request($method, $path, ['form_params' => $parameters]);
            } else {
                $res = $client->request($method, $path, ['query' => $parameters, 'headers' => $headers]);
            }
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
            Log::error('APIRequestCommon:sendRequest:'.$path);
//            throw new Exception($e->getMessage());
        }

        return [
            "statusCode" => $res->getStatusCode(),
            "result"     => $res->getBody()->getContents(),
        ];
    }
}
