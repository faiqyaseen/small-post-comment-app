@extends('user.layouts.app')
@section('css-section')
    <style>
        body {
            background-color: black
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <img class="post_img" src="{{ asset('images/post/' . $data->image) }}"  style="max-width: 100%;" alt="">
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-light"><b>{{ $data->title }}</b></h1>
                    <p class="text-light"> Posted By: {{ $data->user_id == Auth::user()->id ? 'You' : $data->user_name }}</p>
                    <hr>
                    <p class="text-light">{{ $data->description }}</p>
                    <hr>
                    <h3 class="text-light">Comments</h3>
                    @foreach ($comments as $comment)
                        <div class="row">
                            <div class="col-md-2">
                                <p class="text-light text-end">{{ $comment->user_id == Auth::user()->id ? 'You' : $comment->user_name }}</p>
                            </div>
                            <div class="col-md-10">
                                <p class="text-light">{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script-section')

@endsection