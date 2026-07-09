<?php
namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Requests\Admin\CommentRequest;
use App\Setting;
use System\Auth\Auth;

class CommentsController extends AdminController
    {

    public function index()
        {
        $comments = Comment::all();
        return view('admin.comments.index', compact('comments'));
        }

    public function show($id)
        {
        $comment = Comment::find($id);
        return view('admin.comment.show', compact('comment'));
        }

    public function approved($id)
        {
        $comment = Comment::find($id);
        // $enableAbroved = Setting::where('key_meta' , 'comment_approved')->get();
        // if($enableAbroved){
        //     if($enableAbroved[0]->value_meta == 'enable'){
        //         if($comment->approved == 0){
        //         Comment::update(['id' => $id , 'approved' => 1]);
        //         }else{
        //             Comment::update(['id' => $id , 'approved' => 0]);
        //         }
        //     }
        // }
        if ($comment->approved == 0) {
            Comment::update(['id' => $id, 'approved' => 1]);
            }
        else {
            Comment::update(['id' => $id, 'approved' => 0]);
            }
        return back();
        }

        public function aswer($id){
            $comment = Comment::find($id);
            $request = new CommentRequest();
            $inputs = $request->all();
            $inputs['user_id'] = Auth::user()->id;
            $inputs['post_id'] = $comment->post_id;
            $inputs['parent_id'] = $id;
            $inputs['approved'] = 1;
            $inputs['status'] = 0;
            Comment::create($inputs);
            return back();
        }

    // public function create()
    //     {
    //     $comments = Comment::all();
    //     return view('admin.comment.create', compact('comments'));
    //     }
    // public function store()
    //     {
    //     $request = new CommentRequest();
    //     $inputs = $request->all();

    //     Comment::create($inputs);
    //     return redirect('admin/comment');

    //     }
    public function edit($id)
        {
        $comment = Comment::find($id);
        return view('admin.comments.edit', compact('comment'));
        }

    public function update($id)
        {
        $request = new Comment();
        $inputs = $request->all();

        Comment::update($inputs);
        return redirect('admin/comments');
        }

    public function destroy($id)
        {
        Comment::find($id);
        return back();
        }

    // مشکل
    // وقتی دسته والد رو حذف میکنی, باید دسته های فرزند هم حذف بشوند
    }