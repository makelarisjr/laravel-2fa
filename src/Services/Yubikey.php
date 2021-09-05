<?php

namespace MakelarisJR\Laravel2FA\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Yubikey
{
    private string $api_server;
    private string $client_id;
    private string $secret;
    private Client $client;

    public function __construct(string $client_id, string $secret, string $api_server)
    {
        $this->client_id  = $client_id;
        $this->secret     = base64_decode($secret);
        $this->api_server = $api_server;
        $this->client     = new Client();
    }

    public function verifyOtp(string $otp): bool
    {
        $nonce = md5(Str::random());
        $tries = 3;

        do {
            $status = $this->request($otp, $nonce)['status'];
        } while(--$tries !== 0 && $status !== 'OK');

        return $status === 'OK';
    }

    private function request(string $otp, string $nonce): Collection
    {
        $queryParams = Collection::make([
            'id'    => $this->client_id,
            'otp'   => $otp,
            'nonce' => $nonce
        ]);
        $queryParams->put('h', $this->generateSignature($queryParams->sortKeys()->toArray()));

        $req = $this->client->get(
            "https://{$this->api_server}/wsapi/2.0/verify",
            [
                RequestOptions::QUERY   => $queryParams->toArray(),
                RequestOptions::TIMEOUT => 5
            ]
        );

        if ($req->getStatusCode() !== 200)
        {
            return Collection::make(['status' => 'request failed']);
        }

        $params = $this->parseResponseBody($req->getBody()->getContents());

        if ($params['h'] !== $this->generateSignature($params->except('h')->sortKeys()->toArray()))
        {
            return Collection::make(['status' => 'validation failed']);
        }

        return $params;
    }

    private function parseResponseBody(string $body): Collection
    {
        $params = Collection::make();

        foreach (explode("\r\n", $body) as $param)
        {
            if ($param === "") continue;
            [$key, $value] = explode('=', $param, 2);
            $params->put($key, $value);
        }

        return $params;
    }

    /**
     * @param mixed $data
     */
    private function generateSignature($data): string
    {
        return base64_encode(
            hash_hmac('sha1', urldecode(http_build_query($data)), $this->secret, true)
        );
    }
}