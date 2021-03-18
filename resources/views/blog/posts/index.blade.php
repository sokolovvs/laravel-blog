@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="mx-auto">
                <form id="search-form">
                    @php /** @var array $filters */ @endphp
                    <div class="row">
                        <div class="form-group">
                            <div class="input-group">
                                <input name="search-input" id="search-input" type="search"
                                       value="{{ $filters['search'] ?? '' }}"
                                       class="form-control rounded"
                                       placeholder="Search" aria-label="Search"
                                       aria-describedby="search-addon"/>
                                <button onclick="handleSearch()" type="button" class="btn btn-outline-primary">search
                                </button>
                                <a href="{{ route('posts-list') }}">
                                    <button type="button" class="btn btn-outline-secondary">reset</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    @auth
                        <div class="row">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="include-hidden-input">Include unpublished ?</label>
                                    @php {{
                                        $includeHidden = $filters['include_hidden'] ?? 0;
                                        $includeHidden = $includeHidden ? 1 : 0;
                                    }} @endphp
                                    <input name="include-hidden-input" id="include-hidden-input" type="checkbox"
                                           {{ ($filters['include_hidden'] ?? 0) ? 'checked' : 'unchecked' }}
                                           class="form-control"/>
                                </div>
                            </div>
                        </div>
                    @endauth
                </form>
            </div>
        </div>

        <hr>

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

                        <p class="post-content">{{ $post->truncated_content }}...</p>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>

        <!-- Pager -->
        <div class="clearfix">
            <input id="page-value" value="{{ $filters['page'] ?? $paginator->currentPage() }}" type="hidden">

            @if($paginator->hasMorePages())
                <button onclick="handleNextPage()" class="btn btn-primary float-right">Older Posts &rarr;</button>
            @endif
        </div>
    </div>
@endsection
<script type="text/javascript">
    function handleNextPage() {
        document.getElementById('page-value').setAttribute('value', +document.getElementById('page-value').value + 1)
        handleSearch();
    }

    function handleSearch() {
        open(getQuery(buildNewUrl(getFilterValues())), '_self')
    }

    function buildNewUrl(data) {
        const encodedParams = [];

        for (let item in data)
            encodedParams.push(encodeURIComponent(item) + '=' + encodeURIComponent(data[item]));

        return encodedParams.join('&');
    }

    function getFilterValues() {
        return {
            'search': document.getElementById('search-input').value,
            'page': document.getElementById('page-value').value,
            @auth
            'include_hidden': +$('#include-hidden-input').is(":checked")
            @endauth
        };
    }

    function getQuery(encodedParams) {
        return window.location.origin + window.location.pathname + '?' + encodedParams;
    }
</script>
