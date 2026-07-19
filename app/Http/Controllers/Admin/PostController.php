<?php
namespace App\Http\Controllers\Admin;

use App\Category;
use App\Post;
use App\Http\Requests\Admin\PostRequest;
use System\Config\Config;
use System\Auth\Auth;
use DOMDocument;

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
        $inputs['status'] = 0;


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
            $inputs['cat_id'] = NULL;
            }

        if (empty($inputs['seo_title'])) {
            $html = html_entity_decode($inputs['body']);
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);

            // اعلام UTF-8 به DOMDocument
            $dom->loadHTML('<?xml encoding="UTF-8">' . $html);

            libxml_clear_errors();

            $paragraphs = $dom->getElementsByTagName('p');
            $ptag = '';
            foreach ($paragraphs as $p) {
                $ptag .= $p->textContent . ' ';
                }
            $ptag = trim($ptag);
            $inputs['seo_title'] = mb_substr($ptag, 0, 60, 'UTF-8');
            }

        if (empty($inputs['seo_description'])) {
            $html = html_entity_decode($inputs['body']);
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);

            // اعلام UTF-8 به DOMDocument
            $dom->loadHTML('<?xml encoding="UTF-8">' . $html);

            libxml_clear_errors();

            $paragraphs = $dom->getElementsByTagName('p');
            $ptag = '';
            foreach ($paragraphs as $p) {
                $ptag .= $p->textContent . ' ';
                }
            $ptag = trim($ptag);
            $inputs['seo_description'] = mb_substr($ptag, 0, 180, 'UTF-8');
            }

        if (empty($inputs['summary'])) {
            $html = html_entity_decode($inputs['body']);
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);

            // اعلام UTF-8 به DOMDocument
            $dom->loadHTML('<?xml encoding="UTF-8">' . $html);

            libxml_clear_errors();

            $paragraphs = $dom->getElementsByTagName('p');
            $ptag = '';
            foreach ($paragraphs as $p) {
                $ptag .= $p->textContent . ' ';
                }
            $ptag = trim($ptag);
            $inputs['summary'] = mb_substr($ptag, 0, 300, 'UTF-8');
            dd($inputs['summary']);
            }

        Post::create($inputs);
        return redirect('admin/post');
        }

        
    public function update($id)
        {
        $request = new PostRequest();
        $inputs = $request->all();

        $inputs['user_id'] = Auth::user()->id;
        $inputs['status'] = 0;

        if (empty($request->cat_id)) {
            $inputs['cat_id'] = null;
            }

            if (empty($inputs['seo_title'])) {
            $html = html_entity_decode($inputs['body']);
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);

            // اعلام UTF-8 به DOMDocument
            $dom->loadHTML('<?xml encoding="UTF-8">' . $html);

            libxml_clear_errors();

            $paragraphs = $dom->getElementsByTagName('p');
            $ptag = '';
            foreach ($paragraphs as $p) {
                $ptag .= $p->textContent . ' ';
                }
            $ptag = trim($ptag);
            $inputs['seo_title'] = mb_substr($ptag, 0, 60, 'UTF-8');
            }

        if (empty($inputs['seo_description'])) {
            $html1 = html_entity_decode($inputs['body']);
            $dom1 = new DOMDocument();
            libxml_use_internal_errors(true);

            // اعلام UTF-8 به DOMDocument
            $dom1->loadHTML('<?xml encoding="UTF-8">' . $html1);

            libxml_clear_errors();

            $paragraphs1 = $dom1->getElementsByTagName('p');
            $ptag1 = '';
            foreach ($paragraphs1 as $p1) {
                $ptag1 .= $p1->textContent . ' ';
                }
            $ptag1 = trim($ptag1);
            $inputs['seo_description'] = mb_substr($ptag1, 0, 180 , 'UTF-8');
                        //dd($inputs['seo_description']);

            }

        if (empty($inputs['summary'])) {
            $html2 = html_entity_decode($inputs['body']);
            $dom2 = new DOMDocument();
            libxml_use_internal_errors(true);

            // اعلام UTF-8 به DOMDocument
            $dom2->loadHTML('<?xml encoding="UTF-8">' . $html2);

            libxml_clear_errors();

            $paragraphs2 = $dom2->getElementsByTagName('p');
            $ptag2 = '';
            foreach ($paragraphs2 as $p2) {
                $ptag2 .= $p2->textContent . ' ';
                }
            $ptag2 = trim($ptag2);
            $inputs['summary'] = mb_substr($ptag2, 0, 300,'UTF-8');
            //dd($inputs['summary']);
            }
            //dd($inputs);

        Post::update($inputs);
        return redirect('admin/post');
        }

    public function edit($id)
        {
        $post = Post::find($id);

        $categories = Category::all();


        return view('admin.post.edit', compact('post', 'categories'));
        }
    public function destroy($id)
        {
        Post::delete($id);
        return back();
        }
    }