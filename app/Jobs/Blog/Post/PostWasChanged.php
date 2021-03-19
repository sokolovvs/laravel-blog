<?php

namespace App\Jobs\Blog\Post;

use App\Commands\Blog\Posts\Elastic\DeletePost;
use App\Commands\Blog\Posts\Elastic\IndexPost;
use App\Models\Blog\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;

class PostWasChanged implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $postId;

    /**
     * PostWasChanged constructor.
     * @param string $postId
     */
    public function __construct(string $postId)
    {
        $this->postId = $postId;
    }

    /**
     * Execute the job.
     *
     * @param LoggerInterface $logger
     * @param IndexPost $indexPost
     * @param DeletePost $deletePost
     * @return void
     */
    public function handle(LoggerInterface $logger, IndexPost $indexPost, DeletePost $deletePost)
    {
        $logger->debug(sprintf("Started to handle a %s event for post_id = %s", self::class, $this->postId));

        if ($post = Post::query()->find($this->postId)) {
            $indexPost->save($post);
        } else {
            $deletePost->delete($this->postId);
        }

        $logger->debug(sprintf("Ended to handle a %s event for post_id = %s", self::class, $this->postId));
    }
}
