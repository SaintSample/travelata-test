<?php

namespace src\Integrations;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use src\DTO\Integrations\DataProviderDTO;
use src\DTO\Integrations\DataProviderDTOContract;

class DataProvider implements DataProviderContract
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @param string $host
     * @param string $user
     * @param string $pass
     */
    public function __construct(
        string $host,
        string $user,
        string $pass,
    )
    {
        $this->client = new Client([
            'base_uri' => $host,
            'headers' => [
                'Authorization' => ['Basic ' . base64_encode($user . ':' . $pass)],
            ]
        ]);
    }

    /**
     * @return DataProviderDTOContract
     * @throws GuzzleException
     */
    public function get(array $input): DataProviderDTOContract
    {
        return DataProviderDTO::fromArray(json_decode(
            $this->client->request('GET', 'some_uri', ['query' => $input])
                ->getBody()
                ->getContents(),
            true
        ));
    }
}