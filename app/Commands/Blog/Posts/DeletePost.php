<?php


namespace App\Commands\Blog\Posts;


use App\Exceptions\Command\CommandException;
use App\Jobs\Blog\Post\PostWasCreated;
use App\Jobs\Blog\Post\PostWasDeleted;
use App\Queries\Blog\Posts\GetPostById;
use Psr\Log\LoggerInterface;
use Throwable;

class DeletePost
{
    private LoggerInterface $logger;
    private GetPostById $getPostById;

    public function __construct(LoggerInterface $logger, GetPostById $getPostById)
    {
        $this->logger = $logger;
        $this->getPostById = $getPostById;
    }

    public function execute($id): void
    {
        try {
            $post = $this->getPostById->find($id, null);
            $this->logger->debug('The post with id = ' . $id . 'was found, try delete');
            $post->delete();
            $this->logger->debug('The post with id = ' . $id . 'was deleted successfully');
        } catch (Throwable $throwable) {
            $this->logger->alert($throwable->getMessage() . PHP_EOL . $throwable->getTraceAsString());
            CommandException::fromError($throwable);
        }

        try {
            $this->logger->debug(sprintf("The system is going to register a event %s", PostWasCreated::class));
            dispatch(new PostWasDeleted($id));
            $this->logger->debug(sprintf("Event %s was registered", PostWasCreated::class));
        } catch (Throwable $throwable) {
            $this->logger->warning(sprintf("The system could not register a event %s", PostWasCreated::class));
        }
    }
}
