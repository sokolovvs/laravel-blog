<?php

namespace App\Queries\Blog\Tags;

use App\Models\Blog\Tag;
use Illuminate\Support\Collection;

class TagsSearch
{
    /**
     * @param array $params
     * @return Collection|Tag[]
     */
    public function find(array $params): Collection
    {
        $ids = $params['ids'] ?? [];
        $name = $params['name'] ?? null;

        $qb = Tag::query();

        if ($ids) {
            $qb->whereIn('id', $ids);
        }

        if ($name) {
            $qb->where('name', 'LIKE', "%$name%");
        }

        return $qb->get();
    }
}
