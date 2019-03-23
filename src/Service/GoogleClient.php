<?php

namespace App\Service;

class GoogleClient
{
    /** @var \Google_Client $client */
    protected $client;

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->client = new \Google_Client($config);
    }

    /**
     * @return \Google_Client
     */
    public function getClient(): \Google_Client
    {
        return $this->client;
    }

    /**
     * @param null $idToken
     *
     * @return array|false
     */
    public function verifyIdToken($idToken = null)
    {
        return $this->client->verifyIdToken($idToken);
    }
}
