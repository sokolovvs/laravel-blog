<?php


namespace App\Commands\Blog\Posts;


use App\Exceptions\Command\CommandException;
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
    }
}
