@foreach ($posts as $post)
    <div class="col-md-8">
        <div class="card my-3 shadow">
            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-11">
                        <h3><a href="">{{ $post->title }}</a></h3>
                    </div>
                    <div class="col-md-1">
                        <div class="dropdown">
                            <button class="dropdown-toggle btn float-end" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
                            </button>
                            <ul class="dropdown-menu">
                                @if ($post->user_id == Auth::user()->id)
                                <li><a class="dropdown-item" href="#">Edit Post</a></li>
                                <li><a class="dropdown-item" href="#">Delete Post</a></li>
                                @endif
                                <li><a class="dropdown-item" href="#">Show Post</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p>Posted by: {{ ' ' . ($post->user_id) == Auth::user()->id ? 'You' : $post->user_name}}</p>
            </div>
            <div class="card-body">
                <div class="row justify-content-between">
                    <div class="col-md-9">
                        <p id="post-{{ $post->id }}">{{ Str::limit($post->description, 60) }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-end">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if(strlen($post->description) > 60)
                <div class="mb-2">
                    <button id="btn-{{ $post->id }}" onclick="readMore('{{ $post->description }}', {{ $post->id }})" class="btn btn-sm btn-success">Read More</button>
                </div>
                @endif
                <img class="post_img" src="{{ asset('images/post/' . $post->image) }}"  style="width: 100%; max-height: 450px" alt="">
                <div class="mt-2">
                    <form action="{{ route('home.add-comment', $post->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" name="comment" placeholder="Add Comment" required class="form-control" style="border-radius: 25px 25px 25px 25px">
                            </div>
                            <div class="col-md-2 d-grid gap-2 mx-auto">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="row">
                        @foreach($comments as $comment)
                        @if ($comment->post_id == $post->id)
                        <div class="col-md-2">
                            <p class="text-end">{{ ($comment->user_id) == Auth::user()->id ? 'You' : $comment->user_name }}</p>
                        </div>
                        <div class="col-md-10">  
                            <p>{{ $comment->comment }}</p>      
                            <hr>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach