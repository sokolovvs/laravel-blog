<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Queries\Blog\Tags\TagsSearch;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function getByParams(Request $request, TagsSearch $tagsSearch)
    {
        $params = $request->all(['search']);
        $tags = $tagsSearch->find($params);

        return response()->view('blog.tags.index', ['tags' => $tags]);
    }
}
