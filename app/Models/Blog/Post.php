<?php

namespace App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Post
 * @package App\Models\Blog
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property bool $is_publish
 * @property User $author
 * @property string $created_at
 * @property string $updated_at
 * @property string $truncated_content
 * @property Tag[]|Collection $tags
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'content', 'is_publish', 'author_id'];

    public $incrementing = false;

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function getUrl(): string
    {
        return sprintf("%s?id=%s", route('posts-detail'), $this->id);
    }
}
