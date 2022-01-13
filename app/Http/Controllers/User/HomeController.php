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
            $view = view('user.post.post_data', compact('posts', 'comments'))->render();
            return response()->json(['html' => $view]);
        }

        return view('user.home', compact('posts', 'comments'));
    }

    public function myPosts(Request $request)
    {
        $id = Auth::user()->id;
        $posts = Post::getPaginatePosts(['users.id' => $id], 5);

        if($request->ajax()) {
            $view = view('user.post.mypost_data', compact('posts'))->render();
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

        $check = Post::create($data);

        if ($check) {
            $response = $check;
        } else {
            $response = ['status', 'fail'];
        }

        return response()
            ->json($response);
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

        if($data == '') {
            if($request->ajax()){
                return response()->json(['status', 'fail']);
            }
            return 'may be this post is deleted....';
        }

        if($request->ajax()){
            return response()->json($data);
        }

        return view('user.post.single_post', compact('data', 'comments'));
    }

    public function deletePost($id)
    {
        $data = Post::where(['id' => $id, 'user_id' => Auth::user()->id])->first();

        if ($data != '') {
            Comment::where('post_id', $id)->delete();
            Post::where('id', $id)->delete();
        } else {
            return response()->json(['status' => 'error']);
        }

        return response()->json(['status' => 'success']);
    }

    public function updatePost(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $data = $request->except([
            '_token',
            '_method',
            'image',
            'prevImage'
        ]);

        if ($request->hasFile('image')) {
            if(isset($request->prevImage)) {
                if (file_exists(public_path('/images/post'.$request->prevImage))) {
                    unlink(public_path('/images/post'.$request->prevImage));
                }
            }

            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $ImageName = 'post-' . time(). '.' . $extension;
            $image->move(public_path('/images/post'), $ImageName);

            $data['image'] = $ImageName;
        } else {
            $data['image'] = $request->prevImage;
        }

        $check = Post::where(
            [
                'id' => $request->id,
                'user_id' => Auth::user()->id
            ]
        )->update($data);

        if ($check) {
            return response()
            ->json(Post::find($request->id));
        }

        return response()->json(['status', 'error']);
    }
}
