<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Post;
use App\Http\Requests\Admin\PostRequest;

class PostController extends AdminController{
    public function index(){
        $posts = Post::all();
        return view('admin.post.index' , compact('posts'));
    }
    public function create1(){
        $categories = Category::all();
        return view('admin.post.create_editor1' , compact('categories'));
    }
public function create2(){
        $categories = Category::all();
        return view('admin.post.create_editor2' , compact('categories'));
    }
    public function store(){
        $request = new PostRequest();
        $inputs = $request->all();

        Post::create($inputs);
        return redirect('admin/post');
    }
    public function update(){

    }
    public function edit(){

    }
    public function destroy(){

    }
}