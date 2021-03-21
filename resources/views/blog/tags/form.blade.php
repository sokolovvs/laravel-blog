@extends('layouts.app')

@php
    /**
     *  @var \App\Models\Blog\Tag $tag
     */
@endphp
@section('content')
    <div class="container">
        <p class="errors"> {{ $err_message ?? '' }}</p>
        <form method="POST" action="{{$tag ?? null ? route('update-tag') : route('create-tag')}}">
            @csrf
            <input type="hidden" name="id" value="{{ $tag->id ?? '' }}">
            <div class="form-group">
                <label for="name_input">Name</label>
                <input name="name" id="name_input" type="text" class="form-control" value="{{ $tag->name ?? '' }}">
                @if(isset($errs['name']))
                    <p class="errors">{{ implode('', $errs['name']) }}</p>
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
