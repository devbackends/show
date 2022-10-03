<?php

namespace Devvly\Ffl\Services\NutShell;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;

class Api
{
    /**
     *
     */
    const NEW_CONTACT_METHOD = 'newContact';

    /**
     *
     */
    const SUB_URL = '/api/v1/json/';

    /**
     *
     */
    const NEW_LEAD_METHOD = 'newLead';

    /** @var ClientInterface */
    private $client;

    /**
     * @var string[]
     */
    private $methods = [
        self::NEW_CONTACT_METHOD,
        self::NEW_LEAD_METHOD,
    ];

    /**
     * @var
     */
    private $url;

    private $isProdEnv;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->isProdEnv =  app()->environment() === 'prod';
    }

    /**
     * Calls a Nutshell API method.
     *
     * Returns the result from that call or, if there was an error on the server,
     * throws an exception.
     *
     * @param string $method
     * @param array|null $params
     * @return array
     * @throws NutshellApiException
     */
    public function call(string $method, array $params)
    {
        if (!$this->isMethodExists($method)) {
            throw new NutshellApiException('Method not allowed');
        }
        if (!$this->isProdEnv) {
            return null;
        }
        /** @var Response $response */
        $response = $this->client->post($this->client->getConfig('base_url'), [
            'body'      => json_encode([
                'method' => $method,
                'params' => $params,
                'id'     => self::generateRequestId(),
            ])
        ]);

        $response = json_decode($response->getBody()->getContents());

        return $response->result;
    }

    /**
     * @param string $method
     * @return bool
     */
    private function isMethodExists(string $method)
    {
        return in_array($method, $this->methods);
    }

    /**
     * @return false|string
     */
    static public function generateRequestId()
    {
        return substr(md5(rand()), 0, 8);
    }
}
