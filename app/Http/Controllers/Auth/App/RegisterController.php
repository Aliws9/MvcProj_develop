<?php

namespace App\Http\Controllers\Auth\App;

use App\Http\Requests\Auth\App\RegisterRequest;
use App\User;
use App\Http\Services\ImageUpload;
use App\Http\Services\MailService;

class RegisterController{
    private $redirectTo = '/login';
    public function view(){
        return view('auth.app.register');
    }
}