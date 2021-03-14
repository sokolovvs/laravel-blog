<?php

namespace App\Jobs\Blog\Post;

use App\Commands\Blog\Posts\Elastic\IndexPost;
use App\Models\Blog\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use Throwable;

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
     * @return void
     */
    public function handle(LoggerInterface $logger, IndexPost $indexPost)
    {
        $logger->debug(sprintf("Start handling a %s event", self::class));

        try {
            $post = Post::query()->find($this->postId);
            $indexPost->save($post);
            $logger->debug(sprintf("Post %s was handled", $this->postId));
        } catch (Throwable $throwable) {
            $logger->alert(sprintf("Handling of event %s was failed", self::class));
        }
    }
}
