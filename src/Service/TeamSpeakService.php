<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TeamSpeakService
{
    public function __construct(
        private readonly string $port,
        private readonly string $apiKey,
        private readonly HttpClientInterface $httpClient
    ) {}

    public function createChannel(string $channelName, int $maxClients = -1): int
    {
        $url = "/1/channelcreate?channel_flag_permanent=1&channel_name=$channelName";

        if (-1 === $maxClients) {
            $url .= "&channel_maxclients=-1&channel_flag_maxclients_unlimited=1";
        } else {
            $url .= "&channel_maxclients=$maxClients&channel_flag_maxclients_unlimited=0";
        }

        $response = $this->makeRequest($url);

        $this->checkResponseForError($response);

        return (int) $response->toArray(false)['body'][0]['cid'];
    }

    public function setTalkPower(int $channelId, int $talkPower): void
    {
        $response = $this->makeRequest(
            "/1/channeladdperm?cid=$channelId&permsid=i_client_needed_talk_power&permvalue=$talkPower"
        );

        $this->checkResponseForError($response);
    }

    public function setMaxClients(int $channelId, int $maxClients = -1): void
    {
        if (-1 === $maxClients) {
            $url = "/1/channeledit?cid=$channelId&channel_maxclients=-1&channel_flag_maxclients_unlimited=1";
        } else {
            $url = "/1/channeledit?cid=$channelId&channel_maxclients=$maxClients&channel_flag_maxclients_unlimited=0";
        }

        $response = $this->makeRequest($url);

        $this->checkResponseForError($response);
    }

    private function makeRequest(string $url): ResponseInterface
    {
        return $this->httpClient->request('GET', "http://127.0.0.1:{$this->port}" . $url, [
            'headers' => [
                'x-api-key' => $this->apiKey,
            ],
        ]);
    }

    private function checkResponseForError(ResponseInterface $response): void
    {
        $result = $response->toArray(false);

        if (0 !== $result['status']['code']) {
            throw new Exception($result['status']['message']);
        }
    }
}
