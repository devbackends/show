<?php

namespace Devvly\Ffl\Services\NutShell;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;

class DiscoverUrl
{
    /**
     *
     */
    const ENDPOINT_DISCOVER_URL = 'https://api.nutshell.com/v1/json';

    /**
     *
     */
    const METHOD = 'getApiForUsername';

    /** @var ClientInterface */
    private $client;

    /**
     * @var string
     */
    private $userName;

    /**
     * DiscoverUrl constructor.
     * @param ClientInterface $client
     * @param string $userName
     */
    public function __construct(ClientInterface $client, string $userName)
    {
        $this->client = $client;
        $this->userName = $userName;
    }

    /**
     * @return ClientInterface
     */
    public function discoverUrl(): ClientInterface
    {
        /** @var Response $response */
        $response = $this->client->post(self::ENDPOINT_DISCOVER_URL, [
            'body' => json_encode([
                'method' => 'getApiForUsername',
                'params' => ['username' => $this->userName],
                'id'     => Api::generateRequestId(),
            ]),
        ]);

        $response = json_decode($response->getBody()->getContents());
        if(isset($response->result->api)){
          return new Client(array_merge($this->client->getConfig(), ['base_url' => 'https://' . $response->result->api . '/api/v1/json']));
        }else{
          return new Client(array_merge($this->client->getConfig(), ['base_url' => self::ENDPOINT_DISCOVER_URL]));

        }

    }
}
