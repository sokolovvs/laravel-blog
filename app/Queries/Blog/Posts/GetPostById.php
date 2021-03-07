<?php


namespace App\Queries\Blog\Posts;


use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Model;
use ResourceNotFoundException;

class GetPostById
{
    /**
     * @param $id
     * @return Post|Model
     */
    public function find($id): Post
    {
        $post = Post::query()
            ->where('id', '=', $id)
            ->first();

        if ($post === null) {
            throw new ResourceNotFoundException('Post not found');
        }

        return $post;
    }
}
