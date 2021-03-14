<?php

namespace App\Queries\Blog\Posts\Elastic;

use Elasticsearch\Client;

class PaginatedPostsFromElastic
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function find(array $params): array
    {
        $response = $this->client->search($this->buildQuery($params));

        if ($response['hits']['total']['value'] === 0) {
            return [
                'data' => [],
                'count' => 0,
            ];
        }

        return [
            'data' => $response['hits']['hits'],
            'count' => $response['hits']['total']['value'],
        ];
    }

    private function buildQuery(array $params): array
    {
        $query = $params['search'] ?? '';

        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 15;

        $builtQuery = [
            'index' => config('elasticsearch.index_posts'),
            'body' => [
                '_source' => $this->getSource(),
                'from' => ($page - 1) * $limit,
                'size' => $limit,
                'sort' => [
                    ['created_at' => ['order' => 'desc']],
                ],
                'query' => [
                    'bool' => [
                        'must' => [
                            ['term' => ['is_publish' => true]],
                        ],
                    ],
                ],
                'highlight' => $this->getHighlight(),
            ],
        ];

        if ($query) {
            $builtQuery['body']['query']['bool']['must'][] = [
                'multi_match' => [
                    'query' => $query,
                    'fields' => ['title^3', 'content^3'],
                ],
            ];
        }

        return $builtQuery;
    }

    private function getSource(): array
    {
        return [
            'id',
            'title',
            'content',
            'is_publish',
            'author_id',
            'language',
            'created_at',
            'updated_at'
        ];
    }

    private function getHighlight(): array
    {
        return [
            'pre_tags' => ['<b>'],
            'post_tags' => ['</b>'],
            'fields' => [
                'name' => [
                    'fragment_size' => 300,
                    'type' => 'plain',
                    'number_of_fragments' => 4,
                ],
                'content' => [
                    'fragment_size' => 300,
                    'type' => 'plain',
                    'number_of_fragments' => 4,
                ],
            ],
        ];
    }
}
