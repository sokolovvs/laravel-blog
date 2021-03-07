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
            $this->logger->debug("The system is going to create a post with params " . json_encode($input));
            $post = new Post();
            $post->fill($input);
            $post->save();
            $this->logger->debug('The post was created successfully!');
        } catch (Throwable $throwable) {
            $this->logger->alert($throwable->getMessage() . PHP_EOL . $throwable->getTraceAsString());
            throw CommandException::fromError($throwable);
        }
    }
}
