<?php
namespace App\Http\Requests\Admin;

use System\Request\Request;


class UserRequest extends Request{

    public function rules(){
        return [
            //'username' => "unique:users,username"
            //'title' => "required|max:50|min:3",
            // 'summary' => "max:400",
            // 'seo_title' => "max:200",
            // 'seo_description' => "max:300",
        ];
    }

}