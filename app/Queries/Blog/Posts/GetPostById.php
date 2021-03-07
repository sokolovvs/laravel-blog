<?php


namespace App\Queries\Blog\Posts;


use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Model;
use ResourceNotFoundException;

class GetPostById
{
    /**
     * @param string $id
     * @param bool|null $isPublish , null if both
     * @return Post|Model
     */
    public function find(string $id, ?bool $isPublish = true): Post
    {
        $qb = Post::query()
            ->where('id', '=', $id);

        if (is_bool($isPublish)) {
            $qb->where('is_publish', '=', $isPublish);
        }

        $post = $qb->first();

        if ($post === null) {
            throw new ResourceNotFoundException('Post not found');
        }

        return $post;
    }
}
