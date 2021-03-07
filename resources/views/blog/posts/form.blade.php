@extends('layouts.app')

@php
    use App\Models\Blog\Post;

/**
 *  @var Post $post
 *  @var array $errs
 *  @var string $err_message
 */
@endphp
@section('content')
    <div class="container">
        <p class="errors"> {{ $err_message ?? '' }}</p>
        <form method="POST" method="{{route('create-post')}}">
            @csrf
            <input type="hidden" name="id">
            <div class="form-group">
                <label for="title_input">Title</label>
                <input name="title" id="title_input" type="text" class="form-control">
                @if(isset($errs['title']))
                    <p class="errors">{{ implode('', $errs['title']) }}</p>
                @endif
            </div>

            <div class="form-group">
                <label for="content_input">Content</label>
                <textarea name="content" class="form-control" id="content_input"></textarea>
                @if(isset($errs['content']))
                    <p class="errors">{{ implode('', $errs['content']) }}</p>
                @endif
            </div>

            <div class="form-group">
                <label for="is_publish_input">Publish ?</label>
                <input type="checkbox" name="is_publish" class="form-control" id="is_publish_input" checked>
                @if(isset($errs['is_publish']))
                    <p class="errors">{{ implode('', $errs['is_publish']) }}</p>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection
<style>
    .errors {
        color: red
    }
</style>
