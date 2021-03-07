<?php

namespace App\Queries\Blog\Posts;

use App\Models\Blog\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginatedPostFromPgDb
{
    public function find(array $params): LengthAwarePaginator
    {
        $qb = Post::query()->with('author');

        if ($title = $params['title'] ?? null) {
            $qb->where('title', 'LIKE', "$params%");
        }

        if (isset($params['is_publish']) && is_bool($params['is_publish'])) {
            $qb->where('is_publish', '=', $params['is_publish']);
        }

        $qb->orderByDesc('created_at');

        return $qb->paginate($params['limit'] ?? 10, '*', 'Posts', $params['page'] ?? 1);
    }
}
