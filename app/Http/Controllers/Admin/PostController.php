<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Post;
use App\Http\Requests\Admin\CategoryRequest;

class PostController extends AdminController{
    public function index(){
        
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

    }
    public function update(){

    }
    public function edit(){

    }
    public function destroy(){

    }
}