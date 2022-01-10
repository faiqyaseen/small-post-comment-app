<?php

namespace App\Http\Controllers\User;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $comments = Comment::getComments();
        $posts = Post::getPaginatePosts([], 5);

        if($request->ajax()) {
            $view = view('user.post_data', compact('posts', 'comments'))->render();
            return response()->json(['html' => $view]);
        }

        return view('user.home', compact('posts', 'comments'));
    }

    public function myPosts(Request $request)
    {
        $id = Auth::user()->id;
        $posts = Post::getPaginatePosts(['users.id' => $id], 5);

        if($request->ajax()) {
            $view = view('mypost_data', compact('posts'))->render();
            return response()->json(['html' => $view]);
        }

        return view('user.my_posts', compact('posts'));
    }

    public function addPost(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required'
        ]);

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

        $data['user_id'] =  Auth::user()->id;

        Post::create($data);

        return redirect()
            ->route('dashboard')
            ->with('success','Post has been added successfully.');
    }

    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required'
        ]);

        $comment =  $request->comment;
        $user_id = Auth::user()->id;
        $post_id = $id;

        Comment::create([
            'comment' => $comment,
            'user_id' => $user_id,
            'post_id' => $post_id
        ]);

        return redirect()->back()->with('success', 'Comment has been added succesfully.');
    }

    public function singlePost(Request $request, $id)
    {
        $data = Post::getPosts(['posts.id' => $id])->first();
        $comments = Comment::getComments(['comments.post_id' => $id]);

        if($request->ajax()){
            return response()->json($data);
        }

        return view('user.single_post', compact('data', 'comments'));
    }

    public function deletePost($id)
    {
        $data = Post::where(['id' => $id, 'user_id' => Auth::user()->id])->first();

        if ($data != '') {
            Comment::where('post_id', $id)->delete();
            Post::where('id', $id)->delete();
        } else {
            return 'not permitted';
        }

        return redirect()->back()->with('success', 'Post has been deleted succesfully.');
    }

    public function updatePost(Request $request)
    {
        return response()->json($request->all());
    }
}
