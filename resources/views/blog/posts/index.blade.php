@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            @auth
                <a href="{{ route('create-post') }}">
                    <button type="button" class="btn btn-primary">Add post</button>
                </a>
            @endauth

            <div class="col-lg-8 col-md-10 mx-auto">
                @php /** @var Illuminate\Pagination\LengthAwarePaginator $paginator */ @endphp

                @foreach($paginator->items() as $post)

                    @php /** @var \App\Models\Blog\Post $post */ @endphp
                    <div class="post-preview">
                        <a href="{{ $post->getUrl() }}">
                            <h2 class="post-title">{{ $post->title }}</h2>
                        </a>
                        <p>{{  (new DateTime($post->created_at))->format('d M, Y') }}</p>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>

        <!-- Pager -->
        <div class="clearfix">
            @if($paginator->hasMorePages())
                <a class="btn btn-primary float-right" href="{{$paginator->nextPageUrl()}}">Older Posts &rarr;</a>
            @endif
        </div>
    </div>
@endsection
