@foreach ($posts as $post)
    <div class="col-md-8" id="postDiv{{ $post->id }}">
        <div class="card my-3 shadow" style="position: static">
            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-md-11">
                        <h3><a href="{{ route('home.single-post', $post->id) }}" id="ptitle{{ $post->id }}">{{ $post->title }}</a></h3>
                    </div>
                    <div class="col-md-1">
                        <div class="dropdown">
                            <button class="dropdown-toggle btn float-end" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
                            </button>
                            <ul class="dropdown-menu">
                                @if ($post->user_id == Auth::user()->id)
                                <li><a class="dropdown-item" data-bs-toggle="modal" onclick="editPost({{ $post->id }})" data-bs-target="#editModal" style="cursor: pointer" onmouseover="this.classList.add('bg-warning')" onmouseout="this.classList.remove('bg-warning')">Edit</a></li>
                                <li><a class="dropdown-item" onclick="deletePost({{ $post->id }})"  onmouseover="this.classList.add('bg-danger')" onmouseout="this.classList.remove('bg-danger')">Delete</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('home.single-post', $post->id) }}" onmouseover="this.classList.add('bg-info')" onmouseout="this.classList.remove('bg-info')">Show</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p>Posted by: {{ ' ' . ($post->user_id) == Auth::user()->id ? 'You' : $post->user_name}}</p>
            </div>
            <div class="card-body">
                <div class="row justify-content-between">
                    <div class="col-md-9">
                        <p id="hiddenDesc{{ $post->id }}" class="d-none">{{ $post->description }}</p>
                        <p id="post-{{ $post->id }}">{{ Str::limit($post->description, 60) }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-end">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if(strlen($post->description) > 60)
                <div class="mb-2">
                    <button id="btn-{{ $post->id }}" onclick="readMore({{ $post->id }})" class="btn btn-sm btn-success">Read More</button>
                </div>
                @endif
                <img class="post_img" id="pimage{{ $post->id }}" src="{{ asset('images/post/' . $post->image) }}"  style="width: 100%; max-height: 450px" alt="">
                <div class="mt-2">
                    <form>
                        @csrf
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" id="comment{{ $post->id }}" maxlength="255" name="comment" placeholder="Add Comment" required class="form-control" style="border-radius: 25px 25px 25px 25px">
                            </div>
                            <div class="col-md-2 d-grid gap-2 mx-auto">
                                <button type="button" onclick="addComment({{ $post->id }})" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="row" id="comments{{ $post->id }}">
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