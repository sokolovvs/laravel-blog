<?php

namespace App\Queries\Blog\Posts;

use App\Models\Blog\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class PaginatedPostsFromPgDb
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function find(array $params): LengthAwarePaginator
    {
        $this->logger->debug('The system is going to search posts by params ' . json_encode($params));
        $qb = Post::query()->with('author');

        if ($title = $params['title'] ?? null) {
            $qb->where('title', 'LIKE', "$params%");
        }

        if (isset($params['is_publish']) && is_bool($params['is_publish'])) {
            $qb->where('is_publish', '=', $params['is_publish']);
        }

        $qb->orderByDesc('created_at');

        $qb->select(['*', DB::raw('substr(content, 0, 300) AS truncated_content')]);
        $paginator = $qb->paginate($params['limit'] ?? 10, '*', 'page', $params['page'] ?? 1);

        $this->logger->debug('The system was found posts by params' . json_encode($params));

        return $paginator;
    }
}
