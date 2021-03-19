<?php


namespace App\Commands\Blog\Posts\Elastic;


use Elasticsearch\Client;

class DeletePost
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function delete(string $id)
    {
        $this->client->delete(
            [
                'index' => config('elasticsearch.index_posts'),
                'type' => '_doc',
                'id' => $id,
            ]
        );
    }
}
