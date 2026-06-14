<?php
namespace App\Http\Controllers\Admin;

use App\Category;
use App\Post;
use App\Http\Requests\Admin\PostRequest;
use System\Config\Config;
use System\Auth\Auth;

class PostController extends AdminController
    {
    public function index()
        {
        $posts = Post::all();
        return view('admin.post.index', compact('posts'));
        }
    public function create1()
        {
        $categories = Category::all();
        return view('admin.post.create_editor1', compact('categories'));
        }
    public function create2()
        {
        $categories = Category::all();
        return view('admin.post.create_editor2', compact('categories'));
        }
    public function store()
        {
        $request = new PostRequest();
        $inputs = $request->all();
        $inputs['user_id'] = Auth::user()->id;
        $inputs['status'] = 0 ;


        if ($inputs['radio-3'] === 'publish_now') {

            unset($inputs['radio-3']);
            unset($inputs['published_at']);
            }
        elseif ($inputs['radio-3'] === 'publish_date') {
            unset($inputs['radio-3']);
            // dd($inputs['published_at']);
            
            require_once Config::get('app.BASE_DIR') . '/public/jdf/jdf.php';

            $jalali = $inputs['published_at'];

            // جدا کردن تاریخ
            list($date, $time) = explode(' ', $jalali);
            list($y, $m, $d) = explode('/', $date);
            list($h, $i, $s) = explode(':', $time);

            // تبدیل به میلادی
            $g = jalali_to_gregorian($y, $m, $d);

            $gregorian = sprintf(
                "%04d-%02d-%02d %02d:%02d:%02d",
                $g[0], $g[1], $g[2],
                $h, $i, $s
            );

            $inputs['published_at'] = $gregorian;

            }

        if (empty($request->cat_id)) {
            $inputs['cat_id'] = 0;
            }

        Post::create($inputs);
        return redirect('admin/post');
        }
    public function update()
        {

        }
    public function edit($id)
        {
            $post = Post::find($id);

        $categories = Category::all();
        

        return view('admin.post.edit' , compact('post' , 'categories' ));



        }
    public function destroy()
        {

        }
    }