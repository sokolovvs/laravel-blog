<?php

use App\Http\Controllers\Blog\BlogController;
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
            $router->put('update', [BlogController::class, 'update']);
            $router->delete('delete', [BlogController::class, 'delete']);
        }
    );
});
