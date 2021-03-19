<?php


namespace App\Commands\Blog\Posts;


use App\Exceptions\Command\CommandException;
use App\Jobs\Blog\Post\PostWasCreated;
use App\Queries\Blog\Posts\GetPostById;
use Psr\Log\LoggerInterface;
use Throwable;

class UpdatePost
{
    private LoggerInterface $logger;
    private GetPostById $getPostById;

    public function __construct(LoggerInterface $logger, GetPostById $getPostById)
    {
        $this->logger = $logger;
        $this->getPostById = $getPostById;
    }

    public function execute(array $params = []): void
    {
        try {
            $this->logger->debug("The system is going to update a post with params " . json_encode($params));
            $post = $this->getPostById->find($params['id']);
            $post->fill($params);
            $post->save();
            $this->logger->debug('The post was updated successfully!');
        } catch (Throwable $throwable) {
            $this->logger->alert($throwable->getMessage() . PHP_EOL . $throwable->getTraceAsString());
            throw CommandException::fromError($throwable);
        }

        try {
            $this->logger->debug(sprintf("The system is going to register a event %s", PostWasCreated::class));
            dispatch(new PostWasCreated($post->id));
            $this->logger->debug(sprintf("Event %s was registered", PostWasCreated::class));
        } catch (Throwable $throwable) {
            $this->logger->warning(sprintf("The system could not register a event %s", PostWasCreated::class));
        }
    }
}
