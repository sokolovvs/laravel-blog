<?php

namespace App\Http\Controllers\Blog;

use App\Commands\Blog\Posts\CreatePost;
use App\Commands\Blog\Posts\DeletePost;
use App\Commands\Blog\Posts\UpdatePost;
use App\Exceptions\Command\CommandException;
use App\Exceptions\Query\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddPost;
use App\Queries\Blog\Posts\GetPostById;
use App\Queries\Blog\Posts\PaginatedPostsFromPgDb;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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

    public function update(Request $request, UpdatePost $updatePost)
    {
        try {
            $updatePost->execute($request->all());
        } catch (CommandException $commandException) {

            if ($commandException->getPrevious() instanceof ResourceNotFoundException) {
                abort(404, 'Resource not found');
            }

            abort(500, ' Internal server error');
        }
    }

    public function getById(Request $request, GetPostById $getPostById)
    {
        try {
            return response()->view('blog.posts.detail', ['post' => $getPostById->find($request->get('id'))],);
        } catch (Throwable $throwable) {
            abort(404, 'Resource not found');
        }
    }

    public function getByParams(Request $request, PaginatedPostsFromPgDb $paginatedPostFromPgDb)
    {
        $params = $request->all(['search']);

        if (Auth::check()) {
            $params['include_hidden'] = $request->get('include_hidden');
        }

        $paginator = $paginatedPostFromPgDb->find($params);

        return response()->view('blog.posts.index', ['paginator' => $paginator, 'filters' => $request->query()]);
    }

    public function delete(Request $request, DeletePost $deletePost)
    {
        try {
            $deletePost->execute($request->get('id'));
        } catch (CommandException $commandException) {

            if ($commandException->getPrevious() instanceof ResourceNotFoundException) {
                abort(404, 'Resource not found');
            }

            abort(500, ' Internal server error');
        }
    }
}
