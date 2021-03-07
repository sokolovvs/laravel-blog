<?php

namespace Database\Factories;

use App\Models\Blog\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::query()->get();

        return [
            'id' => Uuid::uuid4()->toString(),
            'author_id' => $users->random(1)->first(),
            'title' => $this->faker->title(),
            'content' => $this->faker->text(random_int(1000, 5000)),
            'is_publish' => $this->faker->boolean(),
        ];
    }
}
