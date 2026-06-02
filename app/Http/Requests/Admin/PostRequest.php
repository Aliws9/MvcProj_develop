<?php
namespace App\Http\Requests\Admin;

use System\Request\Request;


class PostRequest extends Request{

    public function rules(){
        return [
            'title' => "required|max:50|min:3",
            'cat_id' => "exists:categories,id",
            'description' => "max:200"
        ];
    }

}