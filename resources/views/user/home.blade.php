@extends('user.layouts.app')
@section('css-section')
    <style>
        .post_img {
            border-radius: 0 5% 0 5% ;
        }
    </style>
@endsection

@section('content')
<div id="alertMessage" style="position: sticky; top: 0;" class="alert d-none" role="alert"></div>
@if(Session::has('success'))
    <div style="position: sticky; top: 0;" class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2>Add Post</h2>
        <hr>
            <div class="card" style="position: static">
                <div class="card-body">
                    <div class="row">
                        <form id="addForm">
                            @csrf
                            @method('POST')
                            <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control" placeholder="Add post title here...">
                            <textarea name="description" placeholder="Add post description here..." class="form-control" id="description" >{{ old('description') }}</textarea>
                            <input type="file" name="image" onchange="previewPostImage(this)" id="image" class="form-control">
                            <div style="overflow-y: scroll; max-height: 50vh">
                                <img id="postImg" class="mt-2" style="max-width: 100%;" alt="">
                            </div>
                            <div>
                                <button type="button" onclick="addPost()" class="btn btn-primary mt-2 float-end">Add Post</button>
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
        <div class="col-md-8" id="newPost"></div>
        @include('user.post.post_data')
    </div>
</div>
<div class="ajax-load text-center">
    <p><img height="50" width="50" src="{{ asset('images/loader.gif') }}" alt="Loader Gif"> Loading..</p>
</div>

 {{-- Edit Modal  --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="editForm">
                @csrf
                <input type="hidden" name="id" id="edid">
                <div class="row my-3">
                    <div class="col-md-3">
                        <label class="col-form-label" for="edtitle">Post Title</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="title" id="edtitle" placeholder="Post Title">
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-md-3">
                        <label class="col-form-label" for="eddescription">Post Description</label>
                    </div>
                    <div class="col-md-8">
                        <textarea name="description" id="eddescription" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-md-3">
                        <label class="col-form-label" for="edimage">Post Image</label>
                    </div>
                    <div class="col-md-8">
                        <input type="hidden" name="prevImage" id="edprevImage">
                        <input type="file" onchange="editPreviewPostImage(this)" class="form-control" name="image" id="edimage" placeholder="Post Image">
                        <img id="edPostImg" class="mt-2" style="max-height: 150px; max-width: 150px;">
                    </div>
                </div>

                <hr>
                <div class="row my-3">
                    <div class="col-md-3">
                        <label class="col-form-label" for="description">Previous Image</label>
                    </div>
                    <div class="col-md-6">
                        <img class="mt-2" id="prevImage" style="max-height: 150px; max-width: 150px;">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" onclick="updatePost()" class="btn btn-primary">Save changes</button>
            </div>
        </form>
      </div>
    </div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h3>Are you sure you want to delete this post?</h3>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="deletePost" class="btn btn-danger">Delete</button>
            </div>
        </form>
      </div>
    </div>
</div>
@endsection
@section('script-section')
@include('user.post.ajax_add_post')
    <script>

        function deletePost (id) {
            $("#deleteModal").modal("show");
            $("#deletePost").click(function () {
                var url = "{{ route('home.delete-post', ':id') }}";
                url = url.replace(":id", id);
                $.ajax({
                    url: url,
                    type: "GET",
                    error: function (data) {
                        console.log(data);
                    },
                    success: function (data) {
                        if (data.status == 'success') {
                            $("#deleteModal").modal("hide");
                            $("#postDiv"+id).remove();
                            $("#alertMessage").addClass("alert-success").removeClass("d-none").html("Your post has been deleted successfully.")
                        } else {
                            alert("something went wrong....!");
                        }
                        
                    }
                });
            })
        }

        // Edit Work
        function editPost(post_id) {
            var url = '{{ route('home.single-post', ':id') }}';
            url = url.replace(':id', post_id);

            $.ajax({
                url: url,
                data: {id: post_id},
                type: 'GET',
                error: function(data) {
                    console.log(data)
                },
                success: function(data) {
                    $("#edtitle").val(data.title);
                    $("#eddescription").val(data.description);
                    var img_url = '{{ asset("images/post/:img") }}';
                    img_url = img_url.replace(':img', data.image);
                    $("#prevImage").attr('src', img_url);
                    $("#edprevImage").val(data.image);
                    $("#edid").val(data.id);
                }
            })
        }

        function editPreviewPostImage(){
            var post_file = $("#edimage").get(0).files[0];
            if (post_file) {
                var reader = new FileReader();
                reader.onload = function () {
                    $('#edPostImg').attr("src", reader.result);
                }

                reader.readAsDataURL(post_file);
            }
        }

        function updatePost() {
            var formData = new FormData($("#editForm")[0]);
            $.ajax({
                url: '{{ route("update-post") }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                error: function(data){
                    console.log(data);
                },
                success: function(data){
                    $("#editModal").modal("hide");
                    if (data.status != 'error') {
                        $("#ptitle"+data.id).html(data.title);
                        $("#post-"+data.id).html(data.description);
                        $("#hiddenDesc"+data.id).html(data.description);
                        var image = "{{ asset('images/post/:image') }}";
                        image = image.replace(":image", data.image);
                        $("#pimage"+data.id).attr("src", image);
                        $("#btn-"+data.id).removeClass('btn-success').addClass('btn-secondary').html('Read less').attr('onclick', 'readLess('+data.id+')');
                        $("#alertMessage").addClass("alert-success").removeClass("d-none").html("Your post has been updated successfully.")
                    }
                }
            })
        }

        // End Edit Work


        function loadMoreData(page) {
            $.ajax({
                url: '?page=' + page,
                type: 'GET',
                beforeSend: function() {
                    $(".ajax-load").show();
                },
                error: function(data) {
                    console.log(data);
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

        function readMore(id) {
            var description = $("#hiddenDesc"+id).html();
            $("#post-"+id).html(description);
            $("#btn-"+id).removeClass('btn-success').addClass('btn-secondary').html('Read less').attr('onclick', 'readLess('+id+')');
        }

        function readLess(id) {
            var short_des = $("#hiddenDesc"+id).html().substring('0', 60) + '...'; 
            $("#post-"+id).html(short_des);
            $("#btn-"+id).removeClass('btn-secondary').addClass('btn-success').html('Read More').attr('onclick', 'readMore('+id+')');
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