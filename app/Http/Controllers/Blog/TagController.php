<?php

namespace App\Http\Controllers\Blog;

use App\Commands\Blog\Tags\CreateTag as CreateTagCommand;
use App\Exceptions\Command\CommandException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\Tags\CreateTag as CreateTagRequest;
use App\Queries\Blog\Tags\TagsSearch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TagController extends Controller
{
    public function create(CreateTagRequest $request, CreateTagCommand $createPost)
    {
        if ($request->isMethod('POST')) {
            try {
                $request->validateInput();
                $createPost->execute($request->getData());

                return response()->redirectTo(route('tags-list'));
            } catch (ValidationException $validationException) {
                return response()->view('blog.tags.form',
                    ['errs' => $validationException->errors(), 'err_message' => $validationException->getMessage()],
                    Response::HTTP_UNPROCESSABLE_ENTITY);
            } catch (CommandException $commandException) {
                Log::error($commandException->getMessage() . PHP_EOL . $commandException->getTraceAsString());

                return response()->view('blog.tags.form', ['err_message' => 'Operation failed'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return response()->view('blog.tags.form');
    }

    public function getByParams(Request $request, TagsSearch $tagsSearch)
    {
        $params = $request->all(['search']);
        $tags = $tagsSearch->find($params);

        return response()->view('blog.tags.index', ['tags' => $tags]);
    }
}
