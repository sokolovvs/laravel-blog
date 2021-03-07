<?php


namespace App\Commands\Blog\Posts;


use App\Exceptions\Command\CommandException;
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
    }
}
