@php /** @var \App\Models\Blog\Post $post */ @endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        @auth
            <div class="row">
                <div class="form-group">
                    <div class="input-group">
                        <a href="{{ sprintf("%s?id=%s", route('update-post'), $post->id) }}">
                            <button type="button" class="btn btn-warning">Edit</button>
                        </a>
                    </div>
                </div>
            </div>

            <form action="{{ sprintf("%s?id=%s",route('delete-post'), $post->id) }}" method="POST">
                <div class="row">
                    <div class="form-group">
                        <div class="input-group">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            @method('delete')
                            @csrf
                        </div>
                    </div>
                </div>
            </form>
        @endauth
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <div class="post-preview">
                    <a href="{{ $post->getUrl() }}">
                        <h2 class="post-title">{{ $post->title }}</h2>
                    </a>
                    <p>{{  (new DateTime($post->created_at))->format('d M, Y') }}</p>
                    <p class="post-content">
                        {!! $post->content !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
