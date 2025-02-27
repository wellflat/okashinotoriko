<?php

namespace Okashi;

use GuzzleHttp\Client as GuzzleClient;


final class OkashiClient {
    private readonly GuzzleClient $client;
    private array $query;

    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => 'https://sysbird.jp/toriko/api',
            'timeout' => 5.0
        ]);
        $this->query = [
            'apikey' => 'guest',
            'format' => 'json'
        ];
    }

    public function getOkashi(string $keyword, string $year, int $type, int $count = 5, int $offset = 0): mixed
    {
        if (!empty($keyword)) {
            $this->query['keyword'] = $keyword;
        }
        if (!empty($year)) {
            $this->query['year'] = $year;
        }
        if (!empty($type)) {
            $this->query['type'] = $type;
        }
        $this->query['max'] = $count;
        $this->query['offset'] = $offset;
        $response = $this->client->get('', ['query' => $this->query]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getType(): mixed
    {
        $this->query['list'] = 'type';
        $response = $this->client->get('', ['query' => $this->query]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getArea(): mixed
    {
        $this->query['list'] = 'area';
        $response = $this->client->get('', ['query' => $this->query]);
        return json_decode($response->getBody()->getContents(), true);
    }
}
