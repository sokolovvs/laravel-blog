<?php


namespace App\Commands\Blog\Posts;


use App\Exceptions\Command\CommandException;
use App\Models\Blog\Post;
use Psr\Log\LoggerInterface;
use Throwable;

class CreatePost
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $input
     * @throws CommandException
     */
    public function execute(array $input = []): void
    {
        try {
            $post = new Post();
            $post->fill($input);
            $post->save();
        } catch (Throwable $throwable) {
            throw CommandException::fromError($throwable);
        }
    }
}
