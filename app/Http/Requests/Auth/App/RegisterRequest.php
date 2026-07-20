<?php

namespace App\Http\Requests\Auth\App;

use System\Request\Request;

class RegisterRequest extends Request{
    protected function rules(){
        return [
            'email' => 'required|max:100|email|unique:users,email',
            'password' => 'required|min:8',
            'first_name' => 'required|max:64',
            'last_name' => 'required|max:64',
            'username' => 'required|max:64|unique:users,username',
        ];
    }
}