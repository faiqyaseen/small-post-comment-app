<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records = Post::getPaginatePosts([], 5);

        if ($request->ajax()) {
            $records = Post::getPaginatePosts([], 5);
            return view('admin.posts.paginate_data', compact('records'))->render();
        }

        return view('admin.posts.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::get();

        return view('admin.posts.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->except([
            '_token',
            '_method',
            'image'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $ImageName = 'post-' . time(). '.' . $extension;
            $image->move(public_path('/images/post'), $ImageName);

            $data['image'] = $ImageName;
        }

        Post::create($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success','Post has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Post::getPosts(['posts.id'=> $id])->first();

        return view('admin.posts.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::get();
        $data = Post::getPosts(['posts.id'=> $id])->first();

        return view('admin.posts.edit', compact('data', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $data = $request->except([
            '_token',
            '_method',
            'image'
        ]);

        $post = Post::where('id', $id)->first();
        $prev_image = $post->image;

        if ($request->hasFile('image')) {
            if ($prev_image != null) {
                if (file_exists(public_path('images/post/'.$prev_image))) {
                    unlink(public_path('/images/post/'.$prev_image));
                }
            }

            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $ImageName = 'post-' . time(). '.' . $extension;
            $image->move(public_path('/images/post'), $ImageName);

            $data['image'] = $ImageName;
        } else {
            $data['image'] = $prev_image;
            
        }

        Post::where('id', $id)->update($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success','Post has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::where('id', $id)->first();

        if ($post->image != null) {
            if (file_exists(public_path('images/post/'.$post->image))) {
                unlink(public_path('/images/post/'.$post->image));
            }
        }

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success','Post has been deleted successfully.');
    }
}
