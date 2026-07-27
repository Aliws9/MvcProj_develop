<?php

namespace App\Http\Requests\Auth\App;

use System\Request\Request;

class LoginRequest extends Request{
    protected function rules(){
        return [
            'emailOrusername' => 'required|max:100',
            'password' => 'required',       
            ];
    }
}