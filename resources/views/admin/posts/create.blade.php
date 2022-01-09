<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>Add Post</title>
</head>
<body>

    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow mt-5">
                        <div class="card-header">
                            <div class="row justify-content-between">
                                <div class="col-md-4">
                                    <h3 class="float-start">Add Post</h3>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-primary float-end">See posts</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row my-3">
                                    <div class="col-md-3">
                                        <label class="col-form-label" for="title">Post Title</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" value="{{ old('title') }}" name="title" id="title" placeholder="Post Title">
                                        @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-md-3">
                                        <label class="col-form-label" for="description">Post Description</label>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea name="description" id="description" cols="30" rows="5" class="form-control">{{ old('description') }}</textarea>
                                        @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-md-3">
                                        <label class="col-form-label" for="user_id">Post By</label>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="user_id" id="user_id" class="form-control">
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name . ' - ' . $user->email }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-md-3">
                                        <label class="col-form-label" for="image">Post Image</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="file" onchange="previewPostImage(this)" class="form-control" name="image" id="image" placeholder="Post Image">
                                        <img id="postImg" class="mt-2" style="max-height: 150px; max-width: 150px;">
                                    </div>
                                </div>
                                <div class="my-3">
                                    <button type="submit" class="btn btn-primary float-end">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
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

        $('select').select2({
            maximumInputLength: 20
        });
    </script>
</body>
</html>