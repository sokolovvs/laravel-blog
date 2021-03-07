<?php

namespace App\Http\Controllers\Blog;

use App\Commands\Blog\Posts\CreatePost;
use App\Exceptions\Command\CommandException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddPost;
use App\Queries\Blog\Posts\GetPostById;
use App\Queries\Blog\Posts\PaginatedPostFromPgDb;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class BlogController extends Controller
{
    public function create(AddPost $request, CreatePost $createPost)
    {
        if ($request->isMethod('POST')) {
            try {
                $request->validateInput();
                $createPost->execute($request->getData());

                return response()->redirectTo(route('posts-list'));
            } catch (ValidationException $validationException) {
                return response()->view('blog.posts.form',
                    ['errs' => $validationException->errors(), 'err_message' => $validationException->getMessage()],
                    Response::HTTP_UNPROCESSABLE_ENTITY);
            } catch (CommandException $commandException) {
                return response()->view('blog.posts.form', ['err_message' => 'Operation failed'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return response()->view('blog.posts.form');
    }

    public function update()
    {

    }

    public function getById(Request $request, GetPostById $getPostById)
    {
        try {
            return response()->view('blog.posts.detail', ['post' => $getPostById->find($request->get('id'))],);
        } catch (Throwable $notFoundException) {
            abort(404, 'Resource not found');
        }
    }

    public function getByParams(Request $request, PaginatedPostFromPgDb $paginatedPostFromPgDb)
    {
        $paginator = $paginatedPostFromPgDb->find($request->all());

        return response()->view('blog.posts.index', ['paginator' => $paginator]);
    }

    public function delete()
    {

    }
}
