<?php

namespace App\Jobs\Blog\Post;

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
     * @return void
     */
    public function handle(LoggerInterface $logger)
    {
        $logger->debug(sprintf("Start handling a %s event", self::class));
    }
}
