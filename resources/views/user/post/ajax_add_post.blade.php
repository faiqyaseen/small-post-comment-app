<script>
    function addPost() {
    var formData = new FormData($("#addForm")[0]);
    $.ajax({
        url: '{{ route("home.add-post") }}',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        error: function(data){
            console.log(data);
            $("#title").after(`<span class="text-danger error-message">${data.responseJSON.errors.title}</span>`);
            $("#description").after(`<span class="text-danger error-message">${data.responseJSON.errors.description}</span>`);
            $("#image").after(`<span class="text-danger error-message">${data.responseJSON.errors.image}</span>`);
        },
        success: function(data){
            if (data.status != "fail") {
                $(".error-message").remove();
                $("#alertMessage").addClass("alert-success").removeClass("d-none").html("Your post has been added successfully.")
                $("#addForm").trigger("reset");
                $("#postImg").attr("src", "");
                var single_route = "{{ route('home.single-post', ':id') }}";
                single_route = single_route.replace(":id", data.id);
                var del_route = "{{ route('home.delete-post', ':id')  }}";
                del_route = del_route.replace(":id", data.id);
                var limit_desc = data.description.substring(0, 60) + "...";
                var image_src = "{{ asset('images/post/' . ':image') }}";
                image_src = image_src.replace(":image", data.image);
                var newPost = `
            <div class="card my-3 shadow" style="position: static" id="postDiv${data.id}">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-md-11">
                            <h3><a href="${single_route}" id="ptitle${data.id}">${data.title}</a></h3>
                        </div>
                        <div class="col-md-1">
                            <div class="dropdown">
                                <button class="dropdown-toggle btn float-end" type="button"  data-bs-toggle="dropdown" aria-expanded="false">
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" data-bs-toggle="modal" onclick="editPost(${data.id})" data-bs-target="#editModal" style="cursor: pointer" onmouseover="this.classList.add('bg-warning')" onmouseout="this.classList.remove('bg-warning')">Edit</a></li>
                                    <li><a class="dropdown-item" onclick="deletePost(${data.id})" onmouseover="this.classList.add('bg-danger')" onmouseout="this.classList.remove('bg-danger')">Delete</a></li>
                                    <li><a class="dropdown-item" href="${single_route}" onmouseover="this.classList.add('bg-info')" onmouseout="this.classList.remove('bg-info')">Show</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <p>Posted by: You</p>
                </div>
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-md-9">
                            <p id="hiddenDesc${data.id}" class="d-none">${data.description}</p>
                            <p id="post-${data.id}">${limit_desc}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-end">1 second ago</p>
                        </div>
                    </div>
                    <div class="mb-2">
                        <button id="btn-${data.id}" onclick="readMore(${data.id})" class="btn btn-sm btn-success">Read More</button>
                    </div>
                    <img class="post_img" id="pimage${data.id}" src="${image_src}"  style="width: 100%; max-height: 450px" alt="">
                </div>
            </div>
        </div>
    </div>`;
                $("#newPost").append(newPost);
            }
        }
    })
}
</script>