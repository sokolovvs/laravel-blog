@php /** @var \App\Models\Blog\Post $post */ @endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <div class="post-preview">
                    <a href="{{ $post->getUrl() }}">
                        <h2 class="post-title">{{ $post->title }}</h2>
                    </a>
                    <p>{{  (new DateTime($post->created_at))->format('d M, Y') }}</p>
                    <p class="post-content">
                        {{ $post->content }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
