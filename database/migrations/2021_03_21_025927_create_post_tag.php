<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePostTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::create('post_tag', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('post_id')
                    ->nullable(false)
                    ->references('id')
                    ->on('posts')
                    ->onDelete('cascade');
                $table->foreignUuid('tag_id')
                    ->nullable(false)
                    ->references('id')
                    ->on('tags')
                    ->onDelete('cascade');
                $table->unique(['tag_id', 'post_id']);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_tag');
    }
}
