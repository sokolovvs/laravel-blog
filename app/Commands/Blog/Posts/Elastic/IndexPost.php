<?php

namespace App\Commands\Blog\Posts\Elastic;

use App\Models\Blog\Post;
use Elasticsearch\Client;

class IndexPost
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function save(Post $post)
    {
        $this->client->index(
            [
                'index' => config('elasticsearch.index_posts'),
                'body' => $post->toArray(),
            ]
        );
    }
}
