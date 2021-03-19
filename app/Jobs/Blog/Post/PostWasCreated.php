<?php

namespace App\Jobs\Blog\Post;

use App\Commands\Blog\Posts\Elastic\CreatePost;
use App\Models\Blog\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;

class PostWasCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $postId;

    public function __construct(string $postId)
    {
        $this->postId = $postId;
    }

    public function handle(LoggerInterface $logger, CreatePost $createPost)
    {
        $logger->debug(sprintf("Started to handle a %s event for post_id = %s", self::class, $this->postId));
        $post = Post::query()->find($this->postId);

        if (!$post) {
            $logger->warning(sprintf("Post with id =%s was not found", $this->postId));
            return;
        }

        $createPost->execute($post);
        $logger->debug(sprintf("Ended to handle a %s event for post_id = %s successful", self::class, $this->postId));
    }
}
