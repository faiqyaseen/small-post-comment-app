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
                            <div class="d-flex justify-content-center">
                                {!! $records->links() !!}
                            </div>