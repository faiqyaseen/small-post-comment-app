<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Post Details</title>
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
                                    <h3 class="float-start">Post Details</h3>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-secondary float-end">Go Back..</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <dl>
                                <div class="row">
                                    <div class="col-md-2">
                                        <dt>Id</dt>
                                    </div>
                                    <div class="col-md-9">
                                        <dd>{{ $data->id }}</dd>
                                    </div>
                                </div>
                            </dl>
                            <hr>
                            <dl>
                                <div class="row">
                                    <div class="col-md-2">
                                        <dt>Post By</dt>
                                    </div>
                                    <div class="col-md-9">
                                        <dd>{{ $data->user_name . ' - ' . $data->user_email}}</dd>
                                    </div>
                                </div>  
                            </dl>
                            <hr>
                            <dl>
                                <div class="row">
                                    <div class="col-md-2">
                                        <dt>Title</dt>
                                    </div>
                                    <div class="col-md-9">
                                        <dd>{{ $data->title }}</dd>
                                    </div>
                                </div>  
                            </dl>
                            <hr>
                            <dl>
                                <div class="row">
                                    <div class="col-md-2">
                                        <dt>Description</dt>
                                    </div>
                                    <div class="col-md-9">
                                        <dd>{{ $data->description }}</dd>
                                    </div>
                                </div>  
                            </dl>
                            @if ($data->image != null)
                            <hr>
                            <dl>
                                <div class="row">
                                    <div class="col-md-2">
                                        <dt>Image</dt>
                                    </div>
                                    <div class="col-md-9">
                                        <dd><img src="{{ asset('images/post/'.$data->image) }}" style="max-width: 100%" alt="Post Image"></dd>
                                    </div>
                                </div>  
                            </dl>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>