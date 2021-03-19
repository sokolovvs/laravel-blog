<?php


namespace App\Commands\Blog\Posts\Elastic;


use Elasticsearch\Client;
use Psr\Log\LoggerInterface;

class DeletePost
{
    private Client $client;
    private LoggerInterface $logger;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function execute(string $id)
    {
        $response = $this->client->delete([
            'index' => config('elasticsearch.index_posts'),
            'id' => $id,
        ]);
        $this->logger->error(json_encode($response, JSON_THROW_ON_ERROR));
    }
}
