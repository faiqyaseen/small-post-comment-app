@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-primary btn-block">Posts</a>
                    <a href="{{ route('admin.comments.index') }}" class="btn btn-primary btn-block">Comments</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
