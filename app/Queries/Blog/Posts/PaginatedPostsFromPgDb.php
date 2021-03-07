<?php

namespace App\Queries\Blog\Posts;

use App\Models\Blog\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaginatedPostsFromPgDb
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

        $qb->select(['*', DB::raw('substr(content, 0, 300) AS truncated_content')]);

        return $qb->paginate($params['limit'] ?? 10, '*', 'page', $params['page'] ?? 1);
    }
}
