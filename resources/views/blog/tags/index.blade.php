@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            @auth
                <a href="{{ route('create-tag') }}">
                    <button type="button" class="btn btn-primary">Add tag</button>
                </a>
            @endauth

            <div class="col-lg-8 col-md-10 mx-auto">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($tags))
                        @foreach($tags as $number => $tag)
                            @php /** @var \App\Models\Blog\Tag $tag */ @endphp
                            <tr>
                                <td>{{ $number }}</td>
                                <td>{{ $tag->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning">Update</button>
                                    <button type="button" class="btn btn-danger">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">Tags not found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
