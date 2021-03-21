<?php

namespace App\Commands\Blog\Tags;

use App\Exceptions\Command\CommandException;
use App\Models\Blog\Tag;
use Psr\Log\LoggerInterface;
use Throwable;

class CreateTag
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $params
     * @throws CommandException
     */
    public function execute(array $params): void
    {

        try {
            $this->logger->debug("The system is going to create a post with params " . json_encode($params));
            $tag = new Tag();
            $tag->fill($params);
            $tag->save();
            $this->logger->debug('The post was created successfully!');
        } catch (Throwable $throwable) {
            $this->logger->alert($throwable->getMessage() . PHP_EOL . $throwable->getTraceAsString());
            throw CommandException::fromError($throwable);
        }
    }
}
