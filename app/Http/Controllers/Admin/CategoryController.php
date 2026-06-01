<?php
namespace App\Http\Controllers\Admin;

use App\Category;
use App\Post;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends AdminController
    {

    public function index()
        {
        $categories = Category::all();
        return view('admin.category.index', compact('categories'));
        }

    public function create()
        {
        $categories = Category::all();
        return view('admin.category.create', compact('categories'));
        }
    public function store()
        {
        $request = new CategoryRequest();
        $inputs = $request->all();

        if (empty($request->parent_id))
            unset($inputs['parent_id']);

        if (empty($request->description))
            unset($inputs['description']);

        Category::create($inputs);
        return redirect('admin/category');

        }
    public function edit($id)
        {
        $category = Category::find($id);
        $category_all = Category::all();
        return view('admin.category.edit', compact('category', 'category_all'));
        }

    public function update($id)
        {
        $request = new CategoryRequest();
        $inputs = $request->all();

        if (empty($request->description))
            unset($inputs['description']);
        // dd($inputs);
        Category::update($inputs);
        return redirect('admin/category');
        }

    public function destroy($id)
        {
        Category::delete($id);
        return back();
        }

    // مشکل
    // وقتی دسته والد رو حذف میکنی, باید دسته های فرزند هم حذف بشوند
    }