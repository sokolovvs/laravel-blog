<?php


namespace App\Queries\Blog\Posts;


use App\Exceptions\Query\ResourceNotFoundException;
use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Model;
use Psr\Log\LoggerInterface;

class GetPostById
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $id
     * @param bool|null $isPublish , null if both
     * @return Post|Model
     */
    public function find(string $id, ?bool $isPublish = true): Post
    {
        $this->logger->debug(sprintf("The system is going to search a post by params: id = %s, is_publish = %d", $id,
            +$isPublish));

        $qb = Post::query()
            ->where('id', '=', $id);

        if (is_bool($isPublish)) {
            $qb->where('is_publish', '=', $isPublish);
        }

        $post = $qb->first();

        if ($post === null) {
            $this->logger->debug('The post was not found, throw ' . ResourceNotFoundException::class);
            throw new ResourceNotFoundException('Post not found');
        }

        $this->logger->debug('The post was found, id = ' . $post->id);

        return $post;
    }
}
