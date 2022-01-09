<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
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
    public function index()
    {
        $records = Comment::getComments();

        return view('admin.comments.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $posts = Post::get();
        $users = User::get();

        return view('admin.comments.create', compact('posts', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request)
    {
        $data = $request->except([
            '_token',
            '_method',
        ]);

        Comment::create($data);

        return redirect()
            ->route('admin.comments.index')
            ->with('success','Comment has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Comment::getComments(['comments.id' => $id])->first();

        return view('admin.comments.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $posts = Post::get();
        $users = User::get();
        $data = Comment::getComments(['comments.id' => $id])->first();

        return view('admin.comments.edit', compact('data', 'posts', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except([
            '_token',
            '_method',
        ]);

        Comment::where('id', $id)->update($data);

        return redirect()
            ->route('admin.comments.index')
            ->with('success','Comment has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Comment::where('id', $id)->delete();

        return redirect()
            ->route('admin.comments.index')
            ->with('success','Comment has been deleted successfully.');
    }
}
