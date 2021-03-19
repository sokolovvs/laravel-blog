<?php

namespace App\Jobs\Blog\Post;

use App\Commands\Blog\Posts\Elastic\DeletePost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;

class PostWasDeleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $postId;

    public function __construct(string $postId)
    {
        $this->postId = $postId;
    }

    public function handle(LoggerInterface $logger, DeletePost $deletePost)
    {
        $logger->debug(sprintf("Started to handle a %s event for post_id = %s", self::class, $this->postId));
        $deletePost->execute($this->postId);
        $logger->debug(sprintf("Ended to handle a %s event for post_id = %s successful", self::class, $this->postId));
    }
}
