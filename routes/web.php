<?php

use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Blog\TagController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('posts-list'));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'posts'], function (Router $router) {
    $router->get('list', [BlogController::class, 'getByParams'])->name('posts-list');
    $router->get('show', [BlogController::class, 'getById'])->name('posts-detail');

    Route::middleware(['auth'])->group(
        function (Router $router) {
            $router->match(['GET', 'POST'], 'create', [BlogController::class, 'create'])->name('create-post');
            $router->match(['GET', 'PUT'], 'update', [BlogController::class, 'update'])->name('update-post');
            $router->delete('delete', [BlogController::class, 'delete'])->name('delete-post');
        }
    );
});

Route::group(['prefix' => 'tags'], function (Router $router) {
    $router->get('show', [TagController::class, 'getById'])->name('tag-detail');

    Route::middleware(['auth'])->group(
        function (Router $router) {
            $router->get('list', [TagController::class, 'getByParams'])->name('tags-list');
            $router->match(['GET', 'POST'], 'create', [TagController::class, 'create'])->name('create-tag');
            $router->match(['GET', 'PUT'], 'update', [TagController::class, 'update'])->name('update-tag');
            $router->delete('delete', [TagController::class, 'delete'])->name('delete-tag');
        }
    );
});
