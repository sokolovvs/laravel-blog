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
use Illuminate\Support\Facades\Log;
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
                Log::error($commandException->getMessage() . PHP_EOL . $commandException->getTraceAsString());

                return response()->view('blog.posts.form', ['err_message' => 'Operation failed'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return response()->view('blog.posts.form');
    }

    public function update(Request $request, UpdatePost $updatePost, GetPostById $getPostById)
    {
        if ($request->isMethod('PUT')) {
            try {
                $updatePost->execute($request->all());

                return response()->redirectTo($request->url() . '?id=' . $request->get('id'));
            } catch (CommandException $commandException) {

                if ($commandException->getPrevious() instanceof ResourceNotFoundException) {
                    abort(404, 'Resource not found');
                }

                Log::error($commandException->getMessage() . PHP_EOL . $commandException->getTraceAsString());
                abort(500, ' Internal server error');
            }
        }

        return response()->view('blog.posts.form', ['post' => $getPostById->find($request->query('id'))]);
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
        $params = $request->all(['search', 'page']);

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

            Log::error($commandException->getMessage() . PHP_EOL . $commandException->getTraceAsString());
            abort(500, ' Internal server error');
        }

        return redirect(route('posts-list'));
    }
}
