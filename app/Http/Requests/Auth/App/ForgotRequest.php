<?php

namespace App\Http\Requests\Auth\App;

use System\Request\Request;

class ForgotRequest extends Request{
    protected function rules(){
        return [
            'emailOruser' => 'required|max:100',    
            ];
    }
}