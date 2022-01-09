<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Posts</title>
</head>
<body>
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow mt-5">
                        <div class="card-header">
                            <div class="row justify-content-between">
                                <div class="col-md-4">
                                    <h3 class="float-start">Posts</h3>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-primary float-end">Add Post</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success'))
                                <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
                            @endif
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Post By</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $key => $record)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $record->user_name . ' - ' . $record->user_email }}</td>
                                        <td>{{ $record->title }}</td>
                                        <td>{{ Str::limit($record->description ,'50') }}</td>
                                        <td>
                                            @if($record->image != null)
                                                <img src="{{ asset('images/post/'.$record->image) }}" height="100" width="100" alt="Post Image"></td>
                                            @endif
                                        <td>
                                            <a href="{{ route('admin.posts.show', $record->id) }}" class="btn btn-sm btn-info">Show</a>
                                            <a href="{{ route('admin.posts.edit', $record->id) }}" class="btn btn-sm btn-warning">Update</a>
                                            <button class="btn btn-sm btn-danger" onclick="deleteFunction({{ $record->id }})">Delete</button>
                                            <form id="deleteForm{{ $record->id }}" method="POST" action="{{ route('admin.posts.destroy', $record->id) }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script>
        function deleteFunction(id) {
            $("#deleteForm"+id).submit()
        }
    </script>
</body>
</html>