<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Tag
 * @package App\Models
 *
 * @property string $id
 * @property string $name
 * @property Post[]|Collection $posts
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];

    public $incrementing = false;

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag', 'tag_id', 'post_id');
    }
}
