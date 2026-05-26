<?php
namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends AdminController{

    public function index(){
        $categories = Category::all();
        return view('admin.category.index' , compact('categories'));
    }
    public function create(){
        $categories = Category::all();
        return view('admin.category.create' , compact('categories'));
    }
    public function store(){
        $request = new CategoryRequest();
        $inputs = $request->all();
        if(empty($request->parent_id))
            unset($inputs['parent_id']);

        if(empty($request->description))
        unset($inputs['description']);

        Category::create($inputs);
        return redirect('admin/category');
        
    }
    public function edit($id){

    }
    public function update($id){

    }
    public function destroy($id){
        
    }
}