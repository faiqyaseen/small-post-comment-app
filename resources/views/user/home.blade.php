@extends('user.layouts.app')
@section('css-section')
    <style>
        .post_img {
            border-radius: 0 5% 0 5% ;
        }
    </style>
@endsection

@section('content')

<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2>Add Post</h2>
        <hr>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route('home.add-post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="Add post title here...">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <textarea name="description" placeholder="Add post description here..." class="form-control" id="description" >{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="file" name="image" onchange="previewPostImage(this)" id="image" class="form-control">
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div style="overflow-y: scroll; max-height: 50vh">
                                <img id="postImg" class="mt-2" style="max-width: 100%;" alt="">
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary mt-2 float-end">Add Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center" id="postData">
        <h2 class="mt-5">Posts</h2>
        <hr>
        @include('user.post_data')
    </div>
</div>
<div class="ajax-load text-center">
    <p><img height="50" width="50" src="{{ asset('images/loader.gif') }}" alt="Loader Gif"> Loading..</p>
</div>
@endsection
@section('script-section')
    <script>
        function loadMoreData(page) {
            $.ajax({
                url: '?page=' + page,
                type: 'GET',
                beforeSend: function() {
                    $(".ajax-load").show();
                }
            })
            .done(function(data) {
                if(data.html == '') {
                    $(".ajax-load").html(`No more records found.`);
                    return;
                }
                $(".ajax-loader").hide();
                $("#postData").append(data.html);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError){
                alert("server not responding")
            })
        }

        var page = 1;
        $(window).scroll(function(){
            if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                page ++;
                loadMoreData(page);
            }
        })

        function readMore(description, id) {
            var short_desc = $("#post-"+id).html();

            $("#post-"+id).html(description);
            $("#btn-"+id).removeClass('btn-success').addClass('btn-secondary').html('Read less').attr('onclick', `readLess('${description}', '${short_desc}', ${id})`);
        }

        function readLess(description ,short_description, id) {
            $("#post-"+id).html(short_description);
            $("#btn-"+id).removeClass('btn-secondary').addClass('btn-success').html('Read More').attr('onclick', `readMore('${description}', ${id})`);
        }

        function previewPostImage () {
            var post_file = $("#image").get(0).files[0];
            if (post_file) {
                var reader = new FileReader();
                reader.onload = function () {
                    $('#postImg').attr("src", reader.result);
                }

                reader.readAsDataURL(post_file);
            }
        }
    </script>
@endsection